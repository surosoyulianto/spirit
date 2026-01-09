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
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-500 hover:underline" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-indigo-500 hover:underline" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini? Stock akan dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
