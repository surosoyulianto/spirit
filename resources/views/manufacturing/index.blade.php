@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- Main Content -->
        <main class="md:col-span-3 bg-gray-50 p-6 rounded-xl shadow-md">
            <section class="mb-6">
                <h2 class="text-2xl font-semibold mb-4">🛠 Manufacturing Orders</h2>
                <a href="{{ route('manufacturing.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">+ Create</a>
            </section>

            <!-- Table -->
            <section class="bg-white p-4 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">MO Number</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Product</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-4 py-2">{{ $order->mo_number }}</td>
                                <td class="px-4 py-2">{{ $order->product->name }}</td>

                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs rounded 
                                        @if($order->status === 'draft') bg-yellow-200 text-yellow-800 
                                        @elseif($order->status === 'confirmed') bg-blue-200 text-blue-800 
                                        @else bg-green-200 text-green-800 
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-2">
                                    <a href="{{ route('manufacturing.show', $order->id) }}" class="text-blue-500 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </section>
        </main>

    </div>
</div>
@endsection
