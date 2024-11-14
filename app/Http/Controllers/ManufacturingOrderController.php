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
        // Get the manufacturing order ID and materials from the request
        $manufacturingOrderId = $request->input('manufacturingOrderId');
        $materials = $request->input('materials');

        // Prepare an array to store the availability data
        $availability = [];

        DB::beginTransaction();
        try {
            foreach ($materials as $material) {
                // Get the inventory for the given material ID
                $inventory = TbInventory::where('id_bahanbaku', $material['materialId'])->first();
                $toConsume = $material['toConsume'];

                // Calculate reserved quantity based on available stock
                $availableStock = $inventory ? $inventory->on_hand : 0;

                // Ensure that reserved quantity is calculated correctly
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
            DB::rollback(); // Rollback transaction on error
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($availability);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'status' => 'required|in:confirmed,in_progress',
        ]);

        // Find the manufacturing order by ID
        $manufacturingOrder = TbManufacturingOrder::findOrFail($id);

        // Update the status based on the request
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
        // Fetching the BOM details related to the manufacturing order
        $manufacturingOrder->load('tb_bom.tb_bom_details.tb_bahanbaku');

        $bomDetails = $manufacturingOrder->tb_bom->tb_bom_details;

        // Retrieve reserved quantities for each material in the manufacturing order
        $reservedData = $manufacturingOrder->tb_manufacturing_order_details->keyBy('id_bahanbaku');


        // Fetch inventory and key it by 'id_bahanbaku' (assuming 'id_bahanbaku' is the foreign key for the material)
        // Assuming 'id_bahanbaku' links to the material in the BOM details
        $inventory = TbInventory::all()->keyBy('id_bahanbaku');

        // Passing bomDetails, inventory, and manufacturing order data to the view
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

        // Add the 'kode_mo' to the request data
        $request->merge(['kode_mo' => $kodeMo]);

        TbManufacturingOrder::create($request->all());
        Alert::success('Manufacturing Order berhasil ditambahkan');
        return redirect()->route('manufacturing_orders.index');
    }

    public function edit(TbManufacturingOrder $manufacturingOrder)
    {
        $products = TbProduk::with('tb_boms')->get(); // Include BoM items for each product
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
