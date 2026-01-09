@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Add New Product') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambahkan produk baru ke inventori</p>
                </div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <x-input-label for="name" :value="__('Product Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Enter product name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="description" rows="3" placeholder="Enter product description">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- SKU -->
                            <div>
                                <x-input-label for="sku" :value="__('SKU')" />
                                <x-text-input id="sku" class="block mt-1 w-full" type="text" name="sku" :value="old('sku')" required placeholder="Enter SKU" />
                                <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-input-label for="category" :value="__('Category')" />
                                <x-text-input id="category" class="block mt-1 w-full" type="text" name="category" :value="old('category')" placeholder="Enter category" />
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div>
                                <x-input-label for="price" :value="__('Price (Rp)')" />
                                <div class="relative mt-1">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <x-text-input id="price" class="block w-full pl-12" type="number" name="price" :value="old('price')" step="0.01" required placeholder="0" />
                                </div>
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <!-- Stock -->
                            <div>
                                <x-input-label for="stock" :value="__('Stock')" />
                                <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock', 0)" required placeholder="0" />
                                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                            </div>

                            <!-- Min Stock Level -->
                            <div>
                                <x-input-label for="min_stock_level" :value="__('Min Stock Level')" />
                                <x-text-input id="min_stock_level" class="block mt-1 w-full" type="number" name="min_stock_level" :value="old('min_stock_level', 10)" placeholder="10" />
                                <x-input-error :messages="$errors->get('min_stock_level')" class="mt-2" />
                            </div>

                            <!-- Is Raw Material -->
                            <div class="flex items-end">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_raw_material" value="1" {{ old('is_raw_material') ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('This is a raw material') }}</span>
                                </label>
                            </div>

                            <!-- Image -->
                            <div class="md:col-span-2">
                                <x-input-label for="image" :value="__('Product Image')" />
                                <div class="mt-1 flex items-center gap-4">
                                    <div class="h-32 w-32 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-300 dark:border-gray-600">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <input id="image" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-indigo-300" type="file" name="image" accept="image/*" />
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <!-- Active Status -->
                            <div class="md:col-span-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 'checked') }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Product is active') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-3">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button type="submit">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Create Product') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

