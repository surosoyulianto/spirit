@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Detail Invoice</h1>
        <a href="{{ route('invoices.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">← Kembali</a>
    </div>

    <div class="bg-white p-6 rounded shadow space-y-6">
        <!-- Invoice Header -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h2 class="text-lg font-semibold mb-2">Informasi Faktur</h2>
                <div class="space-y-1">
                    <p><strong>Nomor :</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>Tanggal:</strong> {{ $invoice->invoice_date }}</p>
                    <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-sm {{ $invoice->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ ucfirst($invoice->status) }}</span></p>
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold mb-2">Informasi Customer</h2>
                <div class="space-y-1">
                    <p><strong>Nama:</strong> {{ $invoice->customer->name ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $invoice->customer->email ?? '-' }}</p>
                    <p><strong>Telepon:</strong> {{ $invoice->customer->phone ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Item Invoice</h2>
            <table class="min-w-full border text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 border">Deskripsi</th>
                        <th class="px-4 py-2 border">Qty</th>
                        <th class="px-4 py-2 border">Harga Satuan</th>
                        <th class="px-4 py-2 border">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="px-4 py-2 border">{{ $item->description }}</td>
                            <td class="px-4 py-2 border">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="flex justify-end">
            <div class="bg-gray-100 p-4 rounded">
                <p class="text-xl font-bold">Total: Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
