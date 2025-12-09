<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Tampilkan daftar faktur
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    // Form untuk membuat faktur baru
    public function create()
    {
        $customers = Customer::all(); // pastikan tabel & model customer sudah ada
        $products = Product::where('is_active', true)->get();
        return view('invoices.create', compact('customers', 'products'));
    }

    // Simpan faktur baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Simpan invoice
        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'customer_id' => $request->customer_id,
            'invoice_date' => $request->invoice_date,
            'total' => 0, // akan dihitung nanti
            'status' => 'draft',
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            // Check stock availability
            if ($product->stock < $item['quantity']) {
                return back()->withErrors(['items' => 'Insufficient stock for product: ' . $product->name]);
            }

            $subtotal = $item['quantity'] * $item['unit_price'];
            $total += $subtotal;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $subtotal,
            ]);

            // Reduce stock
            $product->decrement('stock', $item['quantity']);
        }

        $invoice->update(['total' => $total]);

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat.');
    }

    // (Opsional) Lihat detail faktur
    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'items');
        return view('invoices.show', compact('invoice'));
    }
}
