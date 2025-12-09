@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- Sidebar -->
        <aside class="md:col-span-1 bg-white shadow-md p-4 space-y-4 rounded-xl">
            <nav class="space-y-2">
                <a href="{{ route('invoices.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium">🧾 Invoices</a>
                <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium">📦 Inventory</a>
                <a href="{{ route('pos.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium">💼 Sales</a>
                <a href="{{ route('manufacturing.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium bg-gray-200">🛠 Manufacturing</a>
                <a href="{{ route('customers.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium">👤 Contacts</a>
                <a href="{{ route('profile') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium">⚙ Settings</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="md:col-span-3 bg-gray-50 p-6 rounded-xl shadow-md">
            <section class="mb-6">
                <h2 class="text-2xl font-semibold mb-4">🛠 Manufacturing Orders</h2>
                <a href="{{ route('manufacturing.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">+ Create</a>
            </section>

            <!-- Tabel Manufacturing -->
            <section class="bg-white p-4 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID Order</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Product</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($manufacturingOrders as $order)
                            <tr>
                                <td class="px-4 py-2">{{ $order['id'] }}</td>
                                <td class="px-4 py-2">{{ $order['product'] }}</td>
                                <td class="px-4 py-2">
                                    <x-badge :type="$order['status']" :label="ucfirst($order['status'])" />
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('manufacturing.show', $order['id']) }}" class="text-blue-500 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </main>

    </div>
</div>
@endsection
