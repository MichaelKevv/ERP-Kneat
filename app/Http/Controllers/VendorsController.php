<?php

namespace App\Http\Controllers;

use App\Models\TbKategori;
use App\Models\TbVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VendorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbVendor::all();
        $title = 'Hapus Vendor';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('vendors.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendors.create');
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
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'email' => 'required|string|max:255'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $vendorData = $request->all();
            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $fileName = $image->store('foto-vendor', 'public');
                $vendorData['foto'] = basename($fileName);
            }

            TbVendor::create($vendorData);

            Alert::success("Success", "Data berhasil disimpan");

            DB::commit();

            return redirect("vendors");
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
    public function show(TbVendor $vendor) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TbVendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbVendor $vendor)
    {
        $messages = [
            'required' => 'Field :attribute wajib diisi.'
        ];
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'email' => 'required|string|max:255'
        ], $messages);

        if ($validator->fails()) {
            Alert::error("Error", $validator);
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();

        try {
            $vendorData = $request->all();

            if ($request->hasFile('foto')) {
                if ($vendor->foto) {
                    Storage::delete('public/foto-vendor/' . $vendor->foto);
                }
                $image = $request->file('foto');
                $fileName = $image->store('foto-vendor', 'public');
                $vendorData['foto'] = basename($fileName);
            } else {
                unset($vendorData['foto']);
            }

            $vendor->update($vendorData);

            DB::commit();

            Alert::success("Success", "Data berhasil diperbarui");

            return redirect("vendors");
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error("Error", "Terjadi kesalahan saat memperbarui data.");
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbVendor $vendor)
    {
        DB::beginTransaction();

        try {
            if ($vendor->foto) {
                Storage::delete('public/foto-vendor/' . $vendor->foto);
            }
            $vendor->delete();

            DB::commit();

            Alert::success("Success", "Data berhasil dihapus");

            return redirect("vendors");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function export()
    {
        // $vendor = TbKepalaSekolah::all();
        // $pdf = Pdf::loadview('vendor.export_pdf', ['data' => $vendor]);
        // return $pdf->download('laporan-kepala-sekolah.pdf');
    }
}
