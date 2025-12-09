@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Daftar Invoice</h2>
        <a href="{{ route('invoices.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Invoice Baru</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full border text-sm">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2 border">Nomor</th>
                <th class="px-4 py-2 border">Nomor Invoice</th>
                <th class="px-4 py-2 border">Customer</th>
                <th class="px-4 py-2 border">Tanggal</th>
                <th class="px-4 py-2 border">Total</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 border">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-2 border">{{ $invoice->customer->name ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $invoice->invoice_date }}</td>
                    <td class="px-4 py-2 border">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border">
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-500 hover:underline">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
