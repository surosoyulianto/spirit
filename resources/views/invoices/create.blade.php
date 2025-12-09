@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-6 border-b pb-2">🧾 Buat Invoice Baru</h2>

        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nomor Invoice</label>
                    <input type="text" name="invoice_number" class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Tanggal Invoice</label>
                    <input type="date" name="invoice_date" class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Customer</label>
                    <select name="customer_id" class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="my-6">

            <h4 class="text-xl font-bold mb-4">📦 Item Invoice</h4>
            <div id="items-container" class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg border item">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Product</label>
                            <select name="items[0][product_id]" class="product-select border p-2 rounded w-full" required>
                                <option value="">-- Pilih Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Quantity</label>
                            <input type="number" name="items[0][quantity]" class="quantity-input border p-2 rounded w-full" min="1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Unit Price</label>
                            <input type="number" name="items[0][unit_price]" class="unit-price-input border p-2 rounded w-full" step="0.01" min="0" readonly required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Subtotal</label>
                            <input type="number" name="items[0][subtotal]" class="subtotal-input border p-2 rounded w-full" step="0.01" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addItem()" class="mt-4 bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded">
                + Tambah Item
            </button>

            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold">
                    💾 Simpan Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = 1;
    function addItem() {
        const container = document.getElementById('items-container');
        const html = `
            <div class="bg-gray-50 p-4 rounded-lg border item mb-2">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Product</label>
                        <select name="items[${itemIndex}][product_id]" class="product-select border p-2 rounded w-full" required>
                            <option value="">-- Pilih Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Quantity</label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="quantity-input border p-2 rounded w-full" min="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Unit Price</label>
                        <input type="number" name="items[${itemIndex}][unit_price]" class="unit-price-input border p-2 rounded w-full" step="0.01" min="0" readonly required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Subtotal</label>
                        <input type="number" name="items[${itemIndex}][subtotal]" class="subtotal-input border p-2 rounded w-full" step="0.01" readonly>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
    }

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const row = e.target.closest('.item');
            const priceInput = row.querySelector('.unit-price-input');
            const quantityInput = row.querySelector('.quantity-input');

            if (price) {
                priceInput.value = price;
                if (quantityInput.value) {
                    updateSubtotal(row);
                }
            }
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input') || e.target.classList.contains('unit-price-input')) {
            updateSubtotal(e.target.closest('.item'));
        }
    });

    function updateSubtotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.unit-price-input').value) || 0;
        const subtotal = quantity * price;
        row.querySelector('.subtotal-input').value = subtotal.toFixed(2);
    }
</script>
@endsection
