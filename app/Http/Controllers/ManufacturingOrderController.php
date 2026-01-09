<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ManufacturingOrder;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ManufacturingOrderController extends Controller
{
    public function index()
    {
        $orders = ManufacturingOrder::with('product')->latest()->paginate(10);
        return view('manufacturing.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        return view('manufacturing.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $mo = ManufacturingOrder::create([
            'mo_number' => 'MO-' . time(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'scheduled_date' => $request->scheduled_date,
            'notes' => $request->notes,
            'status' => 'draft',
        ]);

        // Record inventory movement for planned production
        Inventory::recordMovement(
            $request->product_id,
            $request->quantity,
            'in',
            "Manufacturing Order planned: {$mo->mo_number}",
            'manufacturing_order',
            $mo->id
        );

        return redirect()->route('manufacturing.index')->with('success', 'Manufacturing order created.');
    }

    public function show(ManufacturingOrder $manufacturing)
    {
        $manufacturing->load('product', 'inventories');
        return view('manufacturing.show', compact('manufacturing'));
    }

    public function edit(ManufacturingOrder $manufacturing)
    {
        $products = Product::all();
        return view('manufacturing.edit', compact('manufacturing', 'products'));
    }

    public function update(Request $request, ManufacturingOrder $manufacturing)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $oldQuantity = $manufacturing->quantity;
        $newQuantity = $request->quantity;
        $quantityDiff = $newQuantity - $oldQuantity;

        $manufacturing->update($request->all());

        // Adjust inventory if quantity changed
        if ($quantityDiff != 0) {
            Inventory::recordMovement(
                $request->product_id,
                abs($quantityDiff),
                $quantityDiff > 0 ? 'in' : 'out',
                "Quantity adjustment: {$manufacturing->mo_number}",
                'manufacturing_order',
                $manufacturing->id
            );
        }

        return redirect()->route('manufacturing.index')->with('success', 'Manufacturing order updated.');
    }

    public function destroy(ManufacturingOrder $manufacturing)
    {
        // Delete related inventory records
        $manufacturing->inventories()->delete();
        $manufacturing->delete();
        return redirect()->route('manufacturing.index')->with('success', 'Manufacturing order deleted.');
    }

    /**
     * Update status with inventory integration
     */
    public function updateStatus(Request $request, ManufacturingOrder $manufacturing)
    {
        $newStatus = $request->status;

        if (!$manufacturing->transitionTo($newStatus)) {
            return back()->with('error', 'Invalid status transition.');
        }

        $message = "Status updated to: {$newStatus}";
        if ($newStatus === 'done') {
            $message .= ' - Stock increased by ' . $manufacturing->quantity;
        }

        return back()->with('success', $message);
    }
}
