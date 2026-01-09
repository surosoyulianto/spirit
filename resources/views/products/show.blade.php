@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Product Details') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Informasi lengkap produk</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Edit') }}
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('Back') }}
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Product Image Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Product Image') }}</h3>
                            <div class="relative">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                                @else
                                    <div class="w-full h-64 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-300 dark:border-gray-600">
                                        <svg class="h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            <span class="w-2 h-2 mr-1 bg-green-500 rounded-full"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            <span class="w-2 h-2 mr-1 bg-red-500 rounded-full"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Stock Status Card -->
                            <div class="mt-4 p-4 rounded-lg {{ $product->stock <= 0 ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : ($product->stock <= ($product->min_stock_level ?? 10) ? 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800' : 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800') }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Current Stock</p>
                                        <p class="text-2xl font-bold {{ $product->stock <= 0 ? 'text-red-600' : ($product->stock <= ($product->min_stock_level ?? 10) ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ $product->stock }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Min Level</p>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $product->min_stock_level ?? 10 }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    @if($product->stock <= 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">
                                            Out of Stock
                                        </span>
                                    @elseif($product->stock <= ($product->min_stock_level ?? 10))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                                            In Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Value Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total Value</h3>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $product->stock }} × Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Product Info Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Product Name & SKU -->
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $product->sku }}
                                    </span>
                                    @if($product->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                                            {{ $product->category }}
                                        </span>
                                    @endif
                                    @if($product->is_raw_material)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            Raw Material
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                <!-- Price -->
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Price') }}</span>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Stock -->
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Stock') }}</span>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($product->stock) }}
                                    </p>
                                </div>

                                <!-- Min Stock -->
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Min Level') }}</span>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $product->min_stock_level ?? 10 }}
                                    </p>
                                </div>

                                <!-- Created At -->
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $product->created_at->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $product->created_at->format('H:i') }}
                                    </p>
                                </div>

                                <!-- Updated At -->
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated') }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $product->updated_at->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $product->updated_at->format('H:i') }}
                                    </p>
                                </div>

                                <!-- ID -->
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        #{{ $product->id }}
                                    </p>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($product->description)
                                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                        {{ __('Description') }}
                                    </h4>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $product->description }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Quick Actions') }}</h3>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('inventories.create') }}?product_id={{ $product->id }}&type=in" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('Add Stock') }}
                                </a>
                                <a href="{{ route('inventories.create') }}?product_id={{ $product->id }}&type=out" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                    {{ __('Remove Stock') }}
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('Edit Product') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

