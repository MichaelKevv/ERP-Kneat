<?php

namespace App\Http\Controllers;

use App\Models\TbManufacturingOrder;
use App\Models\Product;
use App\Models\TbBomDetail;
use App\Models\TbInventory;
use App\Models\TbManufacturingOrderDetail;
use App\Models\TbProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class ManufacturingOrderController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $manufacturingOrderId = $request->input('manufacturingOrderId');
        $materials = $request->input('materials');

        $availability = [];

        DB::beginTransaction();
        try {
            foreach ($materials as $material) {
                $inventory = TbInventory::where('id_bahanbaku', $material['materialId'])->first();
                $toConsume = $material['toConsume'];

                $availableStock = $inventory ? $inventory->on_hand : 0;

                $reservedQuantity = min($toConsume, $availableStock);
                $availability[$material['materialId']] = $reservedQuantity;

                TbManufacturingOrderDetail::updateOrCreate(
                    [
                        'id_manufacturing_order' => $manufacturingOrderId,
                        'id_bahanbaku' => $material['materialId']
                    ],
                    [
                        'reserved' => $reservedQuantity,
                        'consumed' => 0
                    ]
                );
                Log::info('Reserved quantity updated', ['reservedQuantity' => $reservedQuantity, 'materialId' => $material['materialId']]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($availability);
    }
    public function startProduction(Request $request)
    {
        $manufacturingOrderId = $request->input('manufacturingOrderId');
        $materials = $request->input('materials');

        $availability = [];

        DB::beginTransaction();
        try {
            foreach ($materials as $material) {
                $reservedQuantity  = $material['reserved'];

                TbInventory::where('id_bahanbaku', $material['materialId'])->decrement('on_hand', $reservedQuantity);

                $orderDetail = TbManufacturingOrderDetail::updateOrCreate(
                    [
                        'id_manufacturing_order' => $manufacturingOrderId,
                        'id_bahanbaku' => $material['materialId']
                    ],
                    [
                        'reserved' => $reservedQuantity,
                        'consumed' => $reservedQuantity
                    ]
                );

                $availability[$material['materialId']] = [
                    'reserved' => $orderDetail->reserved,
                    'consumed' => $orderDetail->consumed,
                ];
                Log::info('Reserved quantity updated', ['reservedQuantity' => $reservedQuantity, 'materialId' => $material['materialId']]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'availability' => $availability,
                'message' => 'Produksi dimulai dengan sukses!',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function doneProduction(Request $request)
    {
        DB::beginTransaction();
        try {
            TbInventory::create([
                'id_produk' => $request->input('idProduk'),
                'on_hand' => $request->input('quantity'),
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Produk sudah selesai diproduksi!',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,in_progress,done',
        ]);

        $manufacturingOrder = TbManufacturingOrder::findOrFail($id);

        $manufacturingOrder->status = $request->status;
        $manufacturingOrder->save();

        return response()->json([
            'message' => 'Status updated successfully!',
            'status' => $manufacturingOrder->status
        ]);
    }

    public function index()
    {
        $manufacturingOrders = TbManufacturingOrder::with('tb_produk')->get();
        $title = 'Hapus Bill Of Materials';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('manufacturing_orders.index', compact('manufacturingOrders'));
    }

    public function show(TbManufacturingOrder $manufacturingOrder)
    {
        $manufacturingOrder->load('tb_bom.tb_bom_details.tb_bahanbaku');
        $bomDetails = $manufacturingOrder->tb_bom->tb_bom_details;
        $reservedData = $manufacturingOrder->tb_manufacturing_order_details->keyBy('id_bahanbaku');
        $inventory = TbInventory::all()->keyBy('id_bahanbaku');
        return view('manufacturing_orders.detail', compact('bomDetails', 'inventory', 'manufacturingOrder', 'reservedData'));
    }


    public function create()
    {
        $products = TbProduk::with('tb_boms.tb_bom_details.tb_bahanbaku')->get();
        return view('manufacturing_orders.create', compact('products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:tb_produk,id',
            'kuantitas_produk' => 'required|integer|min:1',
            'tanggal_produksi' => 'required|date',
            'tanggal_deadline' => 'required|date'
        ]);

        $lastOrder = TbManufacturingOrder::latest()->first();
        $lastNoUrutan = $lastOrder ? (int) substr($lastOrder->kode_mo, 3) : 0;
        $newNoUrutan = str_pad($lastNoUrutan + 1, 3, '0', STR_PAD_LEFT); // Pad with leading zeros
        $kodeMo = 'MO/' . $newNoUrutan;

        $request->merge(['kode_mo' => $kodeMo]);

        TbManufacturingOrder::create($request->all());
        Alert::success('Manufacturing Order berhasil ditambahkan');
        return redirect()->route('manufacturing_orders.index');
    }

    public function edit(TbManufacturingOrder $manufacturingOrder)
    {
        $products = TbProduk::with('tb_boms')->get();
        return view('manufacturing_orders.edit', compact('manufacturingOrder', 'products'));
    }

    public function update(Request $request, TbManufacturingOrder $manufacturingOrder)
    {
        $request->validate([
            'id_produk' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'production_date' => 'required|date',
            'status' => 'required|in:draft,confirmed,in_progress,done,canceled',
        ]);

        $manufacturingOrder->update($request->all());
        Alert::success('Manufacturing Order berhasil diupdate');
        return redirect()->route('manufacturing_orders.index');
    }

    public function destroy(TbManufacturingOrder $manufacturingOrder)
    {
        $manufacturingOrder->delete();
        Alert::success('Manufacturing Order berhasil dihapus');
        return redirect()->route('manufacturing_orders.index')->with('success', 'Manufacturing Order deleted successfully');
    }
}
