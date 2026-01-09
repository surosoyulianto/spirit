<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Inventory;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display the POS index page with products and real-time stock.
     */
    public function index()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        $customers = Customer::all();

        // Get low stock products for alerts
        $lowStockProducts = Product::where('stock', '<=', 10)->get();

        return view('pos.index', compact('products', 'customers', 'lowStockProducts'));
    }

    /**
     * Handle the POS checkout process with inventory integration.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Check stock availability for all items
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$product->name}. Available: {$product->stock}"
                ], 422);
            }
        }

        DB::transaction(function () use ($request) {
            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . time(),
                'customer_id' => $request->customer_id,
                'invoice_date' => now()->toDateString(),
                'total' => 0,
                'status' => 'completed',
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $item['quantity'] * $item['unit_price'];
                $total += $subtotal;

                // Create invoice item
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);

                // Reduce stock
                $product->decrement('stock', $item['quantity']);

                // Create inventory record (out)
                Inventory::recordMovement(
                    $product->id,
                    $item['quantity'],
                    'out',
                    "POS Sale: Invoice {$invoice->invoice_number}",
                    'invoice',
                    $invoice->id
                );
            }

            // Update invoice total
            $invoice->update(['total' => $total]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Checkout successful! Stock has been updated.',
            'redirect' => route('invoices.index')
        ]);
    }

    /**
     * Get product details for POS
     */
    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'product' => $product,
            'stock_status' => $product->stockStatus,
            'is_low_stock' => $product->isLowStock(),
        ]);
    }

    /**
     * Search products for POS
     */
    public function searchProducts(Request $request)
    {
        $query = $request->q;
        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
