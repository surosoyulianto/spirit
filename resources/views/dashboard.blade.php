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
                    <a href="{{ route('manufacturing.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium">🛠 Manufacturing</a>
                    <a href="{{ route('customers.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium">👤 Contacts</a>
                    <a href="{{ route('profile') }}" class="block px-3 py-2 rounded hover:bg-gray-100 font-medium">⚙ Settings</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="md:col-span-3 bg-gray-50 p-6 rounded-xl shadow-md">

                <!-- Header -->
                <section class="mb-6">
                    <h2 class="text-2xl font-semibold mb-4">Welcome to Spirit ERP</h2>
                    <p class="text-gray-600">Manage your business operations efficiently with our integrated ERP system.</p>
                </section>

                <!-- Statistics Cards -->
                <section class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                    <!-- Total Invoices -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">🧾</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ \App\Models\Invoice::count() }}</h3>
                                <p class="text-gray-500 text-sm">Total Invoices</p>
                            </div>
                        </div>
                    </div>

                    <!-- Manufacturing Orders -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">🛠</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ \App\Models\ManufacturingOrder::count() }}</h3>
                                <p class="text-gray-500 text-sm">Manufacturing Orders</p>
                            </div>
                        </div>
                    </div>

                    <!-- Customers -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">👥</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ \App\Models\Customer::count() }}</h3>
                                <p class="text-gray-500 text-sm">Customers</p>
                            </div>
                        </div>
                    </div>

                </section>

                <!-- Quick Actions -->
                <section class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="font-semibold mb-4 text-gray-900">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('invoices.create') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-center">
                                Create New Invoice
                            </a>
                            <a href="{{ route('manufacturing.create') }}" class="block w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center">
                                Create Manufacturing Order
                            </a>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="font-semibold mb-4 text-gray-900">Recent Invoices</h3>

                        @php $recentInvoices = \App\Models\Invoice::latest()->take(3)->get(); @endphp

                        @if($recentInvoices->count())
                            <div class="space-y-2">
                                @foreach($recentInvoices as $invoice)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <div>
                                            <p class="font-medium text-sm">{{ $invoice->invoice_number }}</p>
                                            <p class="text-xs text-gray-500">{{ $invoice->customer->name ?? 'N/A' }}</p>
                                        </div>
                                        <span class="text-sm font-semibold text-green-600">${{ number_format($invoice->total, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No invoices yet</p>
                        @endif
                    </div>
                </section>

                <!-- Recent Activities -->
                <section class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="font-semibold mb-4 text-gray-900">Recent Activities</h3>

                    @if($recentInvoices->count())
                        <div class="space-y-3">
                            @foreach($recentInvoices as $invoice)
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <p class="text-sm text-gray-600">
                                        Invoice <strong>{{ $invoice->invoice_number }}</strong>
                                        created for {{ $invoice->customer->name ?? 'Customer' }}
                                    </p>
                                    <span class="text-xs text-gray-400">
                                        {{ $invoice->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                            <p class="text-sm text-gray-500">No recent activities</p>
                        </div>
                    @endif
                </section>

            </main>
        </div>
    </div>
@endsection
