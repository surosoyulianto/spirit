@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-xl p-6">
        <div class="flex justify-between items-center mb-6 border-b pb-2">
            <h2 class="text-2xl font-bold">Edit Invoice</h2>
            <a href="{{ route('invoices.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nomor Invoice</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('invoice_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Tanggal Invoice</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date) }}" class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Customer</label>
                    <select name="customer_id" class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="my-6">

            <h4 class="text-xl font-bold mb-4">📦 Item Invoice</h4>
            <div id="items-container" class="space-y-4">
                @php $itemIndex = 0; @endphp
                @foreach($invoice->items as $item)
                    <div class="bg-gray-50 p-4 rounded-lg border item mb-2" data-index="{{ $itemIndex }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Product</label>
                                <select name="items[{{ $itemIndex }}][product_id]" class="product-select border p-2 rounded w-full" required>
                                    <option value="">-- Pilih Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Quantity</label>
                                <input type="number" name="items[{{ $itemIndex }}][quantity]" value="{{ $item->quantity }}" class="quantity-input border p-2 rounded w-full" min="1" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Unit Price</label>
                                <input type="number" name="items[{{ $itemIndex }}][unit_price]" value="{{ $item->unit_price }}" class="unit-price-input border p-2 rounded w-full" step="0.01" min="0" readonly required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Subtotal</label>
                                <input type="number" name="items[{{ $itemIndex }}][subtotal]" value="{{ $item->subtotal }}" class="subtotal-input border p-2 rounded w-full" step="0.01" readonly>
                            </div>
                        </div>
                        <button type="button" onclick="removeItem(this)" class="mt-2 text-red-500 text-sm hover:text-red-700">Hapus Item</button>
                    </div>
                    @php $itemIndex++; @endphp
                @endforeach
            </div>

            <button type="button" onclick="addItem()" class="mt-4 bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded">
                + Tambah Item
            </button>

            @error('items')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

            @error('items.*')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror

            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold">
                    💾 Update Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = {{ $itemIndex ?? 0 }};

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
                <button type="button" onclick="removeItem(this)" class="mt-2 text-red-500 text-sm hover:text-red-700">Hapus Item</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
    }

    function removeItem(button) {
        const item = button.closest('.item');
        if (document.querySelectorAll('.item').length > 1) {
            item.remove();
        } else {
            alert('Minimal harus ada 1 item');
        }
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

    // Initialize existing items
    document.querySelectorAll('.item').forEach(row => {
        const priceInput = row.querySelector('.unit-price-input');
        const quantityInput = row.querySelector('.quantity-input');
        if (priceInput.value && quantityInput.value) {
            updateSubtotal(row);
        }
    });
</script>
@endsection

