<?php

namespace App\Http\Controllers;

use App\Models\TbKategori;
use App\Models\TbProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbProduk::all();
        $title = 'Hapus Produk';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('produk.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['kategori'] = TbKategori::all();
        return view('produk.create', $data);
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
            'nama_produk' => 'required|string|max:255',
            'exp' => 'required|string|max:255',
            'harga_jual' => 'required|integer',
            'biaya_produk' => 'required|integer'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $produkData = $request->all();
            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $fileName = $image->store('foto-produk', 'public');
                $produkData['foto'] = basename($fileName);
            }

            TbProduk::create($produkData);

            Alert::success("Success", "Data berhasil disimpan");

            DB::commit();

            return redirect("produk");
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
    public function show(TbProduk $produk)
    {
        $qrImageName = $produk->nama_produk . '.png';
        // if (!Storage::exists('public/barcode/qrcode/' . $qrImageName)) {
        //     $qr = QrCode::format('png')->generate($produk->barcode);
        //     Storage::put('public/barcode/qrcode/' . $qrImageName, $qr);
        // }
        // $qr = QrCode::format('png')->generate($produk->barcode);
        // Storage::put('public/barcode/qrcode/' . $qrImageName, $qr);

        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($produk->barcode, $generator::TYPE_CODE_128);

        return view('produk.detail', compact('produk', 'barcode'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TbProduk $produk)
    {
        $kategori = TbKategori::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbProduk $produk)
    {
        $messages = [
            'required' => 'Field :attribute wajib diisi.'
        ];
        $validator = Validator::make($request->all(), [
            'barcode' => 'required|string|max:255',
            'nama_produk' => 'required|string|max:255',
            'exp' => 'required|string|max:255',
            'harga_jual' => 'required|integer',
            'biaya_produk' => 'required|integer'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $produkData = $request->all();

            if ($request->hasFile('foto')) {
                if ($produk->foto) {
                    Storage::delete('public/foto-produk/' . $produk->foto);
                }
                $image = $request->file('foto');
                $fileName = $image->store('foto-produk', 'public');
                $produkData['foto'] = basename($fileName);
            } else {
                unset($produkData['foto']);
            }

            $produk->update($produkData);

            DB::commit();

            Alert::success("Success", "Data berhasil diperbarui");

            return redirect("produk");
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
    public function destroy(TbProduk $produk)
    {
        DB::beginTransaction();

        try {
            if ($produk->foto) {
                Storage::delete('public/foto-produk/' . $produk->foto);
            }
            $produk->delete();

            DB::commit();

            Alert::success("Success", "Data berhasil dihapus");

            return redirect("produk");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function export()
    {
        // $produk = TbKepalaSekolah::all();
        // $pdf = Pdf::loadview('produk.export_pdf', ['data' => $produk]);
        // return $pdf->download('laporan-kepala-sekolah.pdf');
    }
}
