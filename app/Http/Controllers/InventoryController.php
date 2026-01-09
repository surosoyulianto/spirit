<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::with('product')->latest();

        // Filter by product
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $movements = $query->paginate(20);
        $products = Product::all();

        // Get stock summary per product
        $stockSummary = Product::select('id', 'name', 'stock', 'min_stock_level')
            ->orderBy('stock', 'asc')
            ->get();

        return view('inventories.index', compact('movements', 'products', 'stockSummary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('inventories.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:in,out,adjustment',
            'reason' => 'required|string',
            'date' => 'required|date',
        ]);

        // Create inventory record
        Inventory::create($validated);

        // Update product stock
        $product = Product::find($request->product_id);
        if ($request->type === 'in') {
            $product->increment('stock', $request->quantity);
        } elseif ($request->type === 'out') {
            if ($product->stock < $request->quantity) {
                return back()->withErrors(['quantity' => 'Insufficient stock']);
            }
            $product->decrement('stock', $request->quantity);
        } else {
            // Adjustment - could be positive or negative
            $product->stock = $request->quantity;
            $product->save();
        }

        return redirect()->route('inventories.index')->with('success', 'Inventory movement recorded.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movement = Inventory::with('product')->findOrFail($id);
        return view('inventories.show', compact('movement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $movement = Inventory::findOrFail($id);
        $products = Product::all();
        return view('inventories.edit', compact('movement', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $movement = Inventory::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:in,out,adjustment',
            'reason' => 'required|string',
            'date' => 'required|date',
        ]);

        // Note: Stock adjustment on update is complex
        // For simplicity, we'll just update the record without recalculating stock
        $movement->update($validated);

        return redirect()->route('inventories.index')->with('success', 'Inventory movement updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movement = Inventory::findOrFail($id);
        $movement->delete();
        return redirect()->route('inventories.index')->with('success', 'Inventory movement deleted.');
    }

    /**
     * Get stock report
     */
    public function report(Request $request)
    {
        $products = Product::with('inventories')
            ->get()
            ->map(function ($product) {
                $in = $product->inventories()->in()->sum('quantity');
                $out = $product->inventories()->out()->sum('quantity');
                $adjustment = $product->inventories()->adjustment()->sum('quantity');

                return [
                    'product' => $product,
                    'in' => $in,
                    'out' => $out,
                    'adjustment' => $adjustment,
                    'balance' => $in - $out + $adjustment,
                ];
            });

        return view('inventories.report', compact('products'));
    }
}
