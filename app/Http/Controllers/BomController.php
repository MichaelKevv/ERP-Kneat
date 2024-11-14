<?php

namespace App\Http\Controllers;

use App\Models\TbKategori;
use App\Models\TbBahanBaku;
use App\Models\TbBom;
use App\Models\TbBomDetail;
use App\Models\TbProduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class BomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbBom::all();
        $title = 'Hapus Bill Of Materials';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('bom.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['produk'] = TbProduk::all();
        $data['bahan_baku'] = TbBahanBaku::all();
        return view('bom.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input utama BOM
        $request->validate([
            'id_produk' => 'required|exists:tb_produk,id',
            'reference' => 'required|string|max:255',
            'kuantitas' => 'required|integer|min:1'
        ]);

        // Validasi bahan baku
        $request->validate([
            'id_bahanbaku.*' => 'required|exists:tb_bahanbaku,id',
            'kuantitas_bahanbaku.*' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $bom = TbBom::create([
                'id_produk' => $request->id_produk,
                'reference' => $request->reference,
                'kuantitas' => $request->kuantitas,
            ]);

            if ($request->has('id_bahanbaku') && $request->has('kuantitas_bahan')) {
                foreach ($request->id_bahanbaku as $index => $bahanBakuId) {
                    TbBomDetail::create([
                        'id_bom' => $bom->id,
                        'id_bahanbaku' => $bahanBakuId,
                        'kuantitas_bahan' => $request->kuantitas_bahan[$index]
                    ]);
                }
            }

            DB::commit();
            Alert::success("Success", "Bill of Material berhasil ditambahkan");
            return redirect()->route('bom.index');
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
    public function show(TbBom $bom)
    {
        $bom->load('tb_bom_details.tb_bahanbaku');
        return view('bom.detail', compact('bom'));
    }

    public function bom_structure(TbBom $bom)
    {
        $bom->load('tb_bom_details.tb_bahanbaku');
        return view('bom.bom_structure', compact('bom'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TbBom $bom)
    {
        // Muat detail BOM dengan data bahan baku terkait
        $bom->load('tb_bom_details.tb_bahanbaku');

        // Ambil semua produk dan bahan baku untuk dropdown pilihan
        $produk = TbProduk::all();
        $bahanBaku = TbBahanBaku::all();

        return view('bom.edit', compact('bom', 'produk', 'bahanBaku'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbBom $bom)
    {
        // Update data BOM utama
        $bom->update([
            'id_produk' => $request->id_produk,
            'reference' => $request->reference,
        ]);

        // Update existing bahan baku details
        if ($request->has('bahan_baku')) {
            foreach ($request->bahan_baku as $detailId => $data) {
                $detail = TbBomDetail::find($detailId);
                if ($detail) {
                    $detail->update([
                        'id_bahanbaku' => $data['id_bahanbaku'],
                        'kuantitas_bahan' => $data['kuantitas_bahan'], // Gunakan nilai default jika tidak ditemukan
                    ]);
                }
            }
        }

        // Tambahkan bahan baku baru jika ada
        if ($request->has('new_bahan_baku')) {
            // Periksa apakah new_bahan_baku memiliki semua data yang diperlukan
            foreach ($request->new_bahan_baku as $newData) {
                if (isset($newData['id_bahanbaku']) && isset($newData['kuantitas_bahan'])) {
                    $bom->tb_bom_details()->create([
                        'id_bahanbaku' => $newData['id_bahanbaku'],
                        'kuantitas_bahan' => $newData['kuantitas_bahan'],
                    ]);
                }
            }
        }

        Alert::success("Success", "Bill of Material berhasil diperbarui");

        return redirect()->route('bom.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbBahanBaku $bom)
    {
        DB::beginTransaction();

        try {
            if ($bom->foto) {
                Storage::delete('public/foto-bom/' . $bom->foto);
            }
            $bom->delete();

            DB::commit();

            Alert::success("Success", "Data berhasil dihapus");

            return redirect("bom");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function printPdf(TbBom $bom)
    {
        // Load relasi bahan baku
        $bom->load('tb_bom_details', 'tb_bom_details.tb_bahanbaku');

        // Membuat view untuk PDF
        $pdf = Pdf::loadView('bom.pdf', compact('bom'));

        // Return PDF untuk di-download
        return $pdf->download('BOM_' . $bom->id . '.pdf');
    }
}
