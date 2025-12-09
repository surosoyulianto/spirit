<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosController extends Controller
{
    /**
     * Display the POS index page.
     */
    public function index()
    {
        return view('pos.index');
    }

    /**
     * Handle the POS checkout process.
     */
    public function checkout(Request $request)
    {
        // Basic validation and processing logic
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        // Process the checkout (e.g., create an invoice or sale record)
        // For now, just return a success response
        return response()->json(['message' => 'Checkout successful', 'data' => $validated]);
    }
}
