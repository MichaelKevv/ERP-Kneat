<?php

use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ManufacturingOrderController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\VendorsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::resource('produk', ProdukController::class);
Route::resource('bahan_baku', BahanBakuController::class);
Route::resource('bom', BomController::class);
Route::get('bom/{bom}/bom_structure', [BomController::class, 'bom_structure'])->name('bom.structure');
Route::get('bom/{bom}/print', [BomController::class, 'printPdf'])->name('bom.print');
Route::resource('vendors', VendorsController::class);
Route::resource('inventory', InventoryController::class);
Route::resource('manufacturing_orders', ManufacturingOrderController::class);

Route::post('/check-availability', [ManufacturingOrderController::class, 'checkAvailability'])->name('check-availability');
Route::post('/manufacturing-orders/{id}/update-status', [ManufacturingOrderController::class, 'updateStatus'])->name('update-status');

Route::middleware(['auth'])->group(function () {});
