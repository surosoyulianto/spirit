@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $customer->name }}</h1>
            <a href="{{ route('customers.edit', $customer) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Edit Customer</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-semibold mb-4">Customer Details</h2>
                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                    <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                    <p><strong>Address:</strong> {{ $customer->address }}</p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold mb-4">Invoices</h2>
                    @if($customer->invoices->count() > 0)
                        <ul class="list-disc list-inside">
                            @foreach($customer->invoices as $invoice)
                                <li><a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800">{{ $invoice->invoice_number }}</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No invoices found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('customers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Back to Customers</a>
        </div>
    </div>
@endsection
