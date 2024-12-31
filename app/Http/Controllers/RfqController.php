<?php

namespace App\Http\Controllers;

use App\Models\TbKategori;
use App\Models\TbBahanBaku;
use App\Models\TbRfq;
use App\Models\TbBomDetail;
use App\Models\TbProduk;
use App\Models\TbRfqDetail;
use App\Models\TbVendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RfqController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,rfq_sent,rfq_approved,purchase_order',
        ]);

        $rfq = TbRfq::findOrFail($id);

        $rfq->status = $request->status;
        $rfq->save();

        return response()->json([
            'message' => 'Status RFQ updated successfully!',
            'status' => $rfq->status
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbRfq::all();
        $title = 'Hapus RFQ';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('rfq.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['vendor'] = TbVendor::all();
        $data['bahan_baku'] = TbBahanBaku::all();
        return view('rfq.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_vendor' => 'required|exists:tb_produk,id',
        ]);

        // Validasi bahan baku
        $request->validate([
            'id_bahanbaku.*' => 'required|exists:tb_bahanbaku,id',
            'kuantitas_bahanbaku.*' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $lastOrder = TbRfq::latest()->first();
            $lastNoUrutan = $lastOrder ? (int) substr($lastOrder->kode_rfq, 4) : 0;
            $newNoUrutan = str_pad($lastNoUrutan + 1, 3, '0', STR_PAD_LEFT);
            $kodeRfq = 'RFQ/' . $newNoUrutan;

            $rfq = TbRfq::create([
                'id_vendor' => $request->id_vendor,
                'kode_rfq' => $kodeRfq,
                'tanggal_order' => Carbon::now(),
            ]);

            if ($request->has('id_bahanbaku') && $request->has('kuantitas_bahan') && $request->has('total_harga')) {
                foreach ($request->id_bahanbaku as $index => $bahanBakuId) {
                    TbRfqDetail::create([
                        'id_rfq' => $rfq->id,
                        'id_bahanbaku' => $bahanBakuId,
                        'kuantitas' => $request->kuantitas_bahan[$index],
                        'total' => $request->total_harga[$index]
                    ]);
                }
            }

            DB::commit();
            Alert::success("Success", "RFQ berhasil dibuat");
            return redirect()->route('rfq.index');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error("Error", "Terjadi kesalahan saat menyimpan data." . $e->getMessage());
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TbRfq $rfq)
    {
        $rfq->load('tb_rfq_details.tb_bahanbaku');
        return view('rfq.detail', compact('rfq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TbRfq $rfq)
    {
        $rfq->load('tb_rfq_details.tb_bahanbaku');

        $vendor = TbVendor::all();
        $bahanBaku = TbBahanBaku::all();

        return view('rfq.edit', compact('rfq', 'vendor', 'bahanBaku'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbRfq $rfq)
    {
        // Update data BOM utama
        $rfq->update([
            'id_vendor' => $request->id_vendor,
        ]);

        // Update existing bahan baku details
        if ($request->has('bahan_baku')) {
            foreach ($request->bahan_baku as $detailId => $data) {
                $detail = TbRfqDetail::find($detailId);
                if ($detail) {
                    $detail->update([
                        'id_bahanbaku' => $data['id_bahanbaku'],
                        'kuantitas' => $data['kuantitas_bahan'],
                        'total' => $data['total_harga'],
                    ]);
                }
            }
        }

        if ($request->has('new_bahan_baku')) {
            foreach ($request->new_bahan_baku as $newData) {
                if (isset($newData['id_bahanbaku']) && isset($newData['kuantitas_bahan']) && isset($newData['total_harga'])) {
                    $rfq->tb_rfq_details()->create([
                        'id_bahanbaku' => $newData['id_bahanbaku'],
                        'kuantitas' => $newData['kuantitas_bahan'],
                        'total' => $newData['total_harga'],
                    ]);
                }
            }
        }

        Alert::success("Success", "RFQ berhasil diperbarui");

        return redirect()->route('rfq.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbRfq $rfq)
    {
        DB::beginTransaction();

        try {
            $rfq->tb_rfq_details()->delete();
            $rfq->delete();
            DB::commit();
            Alert::success("Success", "RFQ berhasil dihapus");
            return redirect("rfq");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }


    public function printPdf(TbRfq $rfq)
    {
        $rfq->load('tb_rfq_details', 'tb_rfq_details.tb_bahanbaku');
        $pdf = Pdf::loadView('rfq.pdf', compact('rfq'));
        return $pdf->download('RFQ_' . $rfq->kode_rfq . '.pdf');
    }
}
