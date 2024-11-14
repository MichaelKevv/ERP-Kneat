<?php

namespace App\Http\Controllers;

use App\Models\TbKategori;
use App\Models\TbBahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbBahanBaku::all();
        $title = 'Hapus Bahan Baku';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('bahan_baku.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['kategori'] = TbKategori::all();
        return view('bahan_baku.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => 'Field :attribute wajib diisi.'
        ];
        $validator = Validator::make($request->all(), [
            'barcode' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|integer'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $produkData = $request->all();
            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $fileName = $image->store('foto-bahan-baku', 'public');
                $produkData['foto'] = basename($fileName);
            }

            TbBahanBaku::create($produkData);

            Alert::success("Success", "Data berhasil disimpan");

            DB::commit();

            return redirect("bahan_baku");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TbBahanBaku $bahan_baku)
    {
        $qrImageName = $bahan_baku->nama . '.png';
        // if (!Storage::exists('public/barcode/qrcode/' . $qrImageName)) {
        //     $qr = QrCode::format('png')->generate($bahan_baku->barcode);
        //     Storage::put('public/barcode/qrcode/' . $qrImageName, $qr);
        // }
        // $qr = QrCode::format('png')->generate($bahan_baku->barcode);
        // Storage::put('public/barcode/qrcode/' . $qrImageName, $qr);

        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($bahan_baku->barcode, $generator::TYPE_CODE_128);

        return view('bahan_baku.detail', compact('bahan_baku', 'barcode'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TbBahanBaku $bahan_baku)
    {
        $kategori = TbKategori::all();
        return view('bahan_baku.edit', compact('bahan_baku', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbBahanBaku $bahan_baku)
    {
        $messages = [
            'required' => 'Field :attribute wajib diisi.'
        ];
        $validator = Validator::make($request->all(), [
            'barcode' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|integer'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $produkData = $request->all();

            if ($request->hasFile('foto')) {
                if ($bahan_baku->foto) {
                    Storage::delete('public/foto-bahan_baku/' . $bahan_baku->foto);
                }
                $image = $request->file('foto');
                $fileName = $image->store('foto-bahan-baku', 'public');
                $produkData['foto'] = basename($fileName);
            } else {
                unset($produkData['foto']);
            }

            $bahan_baku->update($produkData);

            DB::commit();

            Alert::success("Success", "Data berhasil diperbarui");

            return redirect("bahan_baku");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbBahanBaku $bahan_baku)
    {
        DB::beginTransaction();

        try {
            if ($bahan_baku->foto) {
                Storage::delete('public/foto-bahan_baku/' . $bahan_baku->foto);
            }
            $bahan_baku->delete();

            DB::commit();

            Alert::success("Success", "Data berhasil dihapus");

            return redirect("bahan_baku");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function export()
    {
        // $bahan_baku = TbKepalaSekolah::all();
        // $pdf = Pdf::loadview('bahan_baku.export_pdf', ['data' => $bahan_baku]);
        // return $pdf->download('laporan-kepala-sekolah.pdf');
    }
}
