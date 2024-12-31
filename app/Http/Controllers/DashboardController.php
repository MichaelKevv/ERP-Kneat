<?php

namespace App\Http\Controllers;

use App\Models\TbBahanbaku;
use App\Models\TbProduk;
use App\Models\TbVendor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $produk = TbProduk::count();
        $bahanbaku = TbBahanbaku::count();
        $vendor = TbVendor::count();
        // $customer = TbProduk::count();
        return view('dashboard', compact('produk', 'bahanbaku', 'vendor'));
    }
}
