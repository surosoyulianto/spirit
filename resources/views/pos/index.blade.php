<?php

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

$todayInvoices = Invoice::whereDate('invoice_date', today())->get();
$todaySales = $todayInvoices->sum('total');
$todayTransactions = $todayInvoices->count();
?>

@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Low Stock Alerts -->
            @if($lowStockProducts->count() > 0)
                <div class="mb-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                {{ __('Low Stock Alert') }}
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <p>{{ __('The following products are running low on stock:') }}</p>
                                <ul class="list-disc list-inside mt-1">
                                    @foreach($lowStockProducts as $product)
                                        <li>{{ $product->name }} - {{ $product->stock }} units remaining</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Point of Sale</h1>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('User:') }} {{ Auth::user()->name }}
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Today's Summary -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __("Today's Summary") }}</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('Total Sales') }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($todaySales, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('Transactions') }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $todayTransactions }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('User') }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Products') }}
                            </a>
                            <a href="{{ route('customers.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Customers') }}
                            </a>
                            <a href="{{ route('invoices.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Invoices') }}
                            </a>
                            <a href="{{ route('inventories.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Stock') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('System Status') }}</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                <span class="text-gray-900 dark:text-white">{{ __('System Online') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                <span class="text-gray-900 dark:text-white">{{ __('Inventory Connected') }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 {{ $lowStockProducts->count() > 0 ? 'bg-yellow-500' : 'bg-green-500' }} rounded-full mr-2"></span>
                                <span class="text-gray-900 dark:text-white">{{ $lowStockProducts->count() > 0 ? 'Low Stock Alert' : 'All Stock OK' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- POS Product Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Products') }}</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="product-grid">
                        @forelse($products as $product)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 product-card {{ $product->stock <= 0 ? 'opacity-50' : '' }}"
                                 data-product-id="{{ $product->id }}"
                                 data-product-name="{{ $product->name }}"
                                 data-product-price="{{ $product->price }}"
                                 data-product-stock="{{ $product->stock }}">
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $product->sku }}</div>
                                    <div class="text-xl font-bold text-indigo-600 mt-2">{{ number_format($product->price, 0, ',', '.') }}</div>
                                    
                                    <div class="mt-2">
                                        @if($product->stock <= 0)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                Out of Stock
                                            </span>
                                        @elseif($product->stock <= 10)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Low: {{ $product->stock }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Stock: {{ $product->stock }}
                                            </span>
                                        @endif
                                    </div>

                                    <button type="button" 
                                            class="mt-3 w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs leading-4 font-medium rounded-md text-white {{ $product->stock <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                                            {{ $product->stock <= 0 ? 'disabled' : '' }}
                                            onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})">
                                        {{ __('Add to Cart') }}
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-4 text-center py-8 text-gray-500 dark:text-gray-400">
                                {{ __('No products available. Please add products first.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Shopping Cart -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Shopping Cart') }}</h3>
                        <button type="button" onclick="clearCart()" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                            {{ __('Clear Cart') }}
                        </button>
                    </div>

                    <form id="checkout-form" action="{{ route('pos.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="items" id="cart-items-input">
                        <input type="hidden" name="customer_id" id="customer-id-input">

                        <div class="mb-4">
                            <x-input-label for="customer_id" :value="__('Customer (Optional)')" />
                            <select name="customer_id" id="customer_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Walk-in Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Product') }}</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Price') }}</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Subtotal') }}</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr id="empty-cart-row">
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('Cart is empty. Add products to get started.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-end items-center">
                            <div class="text-right mr-4">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('Total:') }}</span>
                                <span id="cart-total" class="text-2xl font-bold text-gray-900 dark:text-white ml-2">Rp 0</span>
                            </div>
                            <button type="submit" id="checkout-btn" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                {{ __('Checkout') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

