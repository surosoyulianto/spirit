<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManufacturingOrder;

class ManufacturingController extends Controller
{
    // Tampilkan daftar Manufacturing Orders
    public function index()
    {
        $manufacturingOrders = ManufacturingOrder::latest()->get();
        return view('manufacturing.index', compact('manufacturingOrders'));
    }

    // Tampilkan form tambah Manufacturing Order
    public function create()
    {
        return view('manufacturing.create');
    }

    // Simpan Manufacturing Order baru
    public function store(Request $request)
    {
        $request->validate([
            'id_order' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:draft,confirmed,in_progress,done,cancelled',
        ]);

        ManufacturingOrder::create($request->all());

        return redirect()->route('manufacturing.index')->with('success', 'Order created.');
    }

    // Tampilkan detail Manufacturing Order
    public function show($id)
    {
        $manufacturingOrder = ManufacturingOrder::findOrFail($id);
        return view('manufacturing.show', compact('manufacturingOrder'));
    }

    // Tampilkan form edit Manufacturing Order
    public function edit($id)
    {
        $manufacturingOrder = ManufacturingOrder::findOrFail($id);
        return view('manufacturing.edit', compact('manufacturingOrder'));
    }

    // Update Manufacturing Order
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_order' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:draft,confirmed,in_progress,done,cancelled',
        ]);

        $manufacturingOrder = ManufacturingOrder::findOrFail($id);
        $manufacturingOrder->update($request->all());

        return redirect()->route('manufacturing.index')->with('success', 'Order updated.');
    }

    // Hapus Manufacturing Order
    public function destroy($id)
    {
        $manufacturingOrder = ManufacturingOrder::findOrFail($id);
        $manufacturingOrder->delete();

        return redirect()->route('manufacturing.index')->with('success', 'Order deleted.');
    }
}
