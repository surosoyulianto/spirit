@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                {{ __('Create Manufacturing Order') }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ __('Create a new manufacturing order with raw materials') }}
                            </p>
                        </div>
                        <a href="{{ route('manufacturing.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>

                    <form action="{{ route('manufacturing.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Finished Product Selection -->
                        <div>
                            <x-input-label for="product_id" :value="__('Finished Product')" />
                            <select name="product_id" id="product_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Product') }}</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }}) - Stok: {{ $product->stock }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                        </div>

                        <!-- Quantity -->
                        <div>
                            <x-input-label for="quantity" :value="__('Quantity to Produce')" />
                            <x-text-input id="quantity" name="quantity" type="number" class="mt-1 block w-full" :value="old('quantity', 1)" min="1" required />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <!-- Scheduled Date -->
                        <div>
                            <x-input-label for="scheduled_date" :value="__('Scheduled Date')" />
                            <x-text-input id="scheduled_date" name="scheduled_date" type="date" class="mt-1 block w-full" :value="old('scheduled_date')" />
                            <x-input-error :messages="$errors->get('scheduled_date')" class="mt-2" />
                        </div>

                        <!-- Raw Materials Section -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('Raw Materials') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                {{ __('Select raw materials needed for production. Stock will be deducted when production starts.') }}
                            </p>

                            <div class="space-y-3" id="materials-container">
                                @if(old('materials'))
                                    @foreach(old('materials') as $materialId => $materialData)
                                        @if(!empty($materialData['quantity']))
                                            <div class="material-row flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <div class="flex-1">
                                                    <select name="materials[{{ $loop->index }}][id]" class="material-select w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">{{ __('Select Material') }}</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" {{ $product->id == $materialId ? 'selected' : '' }}>
                                                                {{ $product->name }} ({{ $product->sku }}) - Rp {{ number_format($product->price, 0, ',', '.') }} - Stok: {{ $product->stock }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="w-32">
                                                    <x-text-input type="number" name="materials[{{ $loop->index }}][quantity]" class="material-quantity w-full" :value="$materialData['quantity']" min="1" placeholder="{{ __('Qty') }}" />
                                                </div>
                                                <button type="button" class="remove-material text-red-500 hover:text-red-700" onclick="removeMaterial(this)">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="material-row flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex-1">
                                            <select name="materials[0][id]" class="material-select w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">{{ __('Select Material') }}</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->name }} ({{ $product->sku }}) - Rp {{ number_format($product->price, 0, ',', '.') }} - Stok: {{ $product->stock }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-32">
                                            <x-text-input type="number" name="materials[0][quantity]" class="material-quantity w-full" value="1" min="1" placeholder="{{ __('Qty') }}" />
                                        </div>
                                        <button type="button" class="remove-material text-red-500 hover:text-red-700" onclick="removeMaterial(this)">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <button type="button" id="add-material" class="mt-4 inline-flex items-center px-3 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Add Material') }}
                            </button>
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('manufacturing.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button type="submit">
                                {{ __('Create Manufacturing Order') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let materialIndex = {{ old('materials') ? count(array_filter(old('materials'), function($m) { return !empty($m['quantity']); })) : 1 }};

        document.getElementById('add-material').addEventListener('click', function() {
            const container = document.getElementById('materials-container');
            const products = @json($products);

            const row = document.createElement('div');
            row.className = 'material-row flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg';
            row.innerHTML = `
                <div class="flex-1">
                    <select name="materials[${materialIndex}][id]" class="material-select w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('Select Material') }}</option>
                        ${products.map(p => `<option value="${p.id}">${p.name} (${p.sku}) - Rp ${new Intl.NumberFormat('id-ID').format(p.price)} - Stok: ${p.stock}</option>`).join('')}
                    </select>
                </div>
                <div class="w-32">
                    <x-text-input type="number" name="materials[${materialIndex}][quantity]" class="material-quantity w-full" value="1" min="1" placeholder="{{ __('Qty') }}" />
                </div>
                <button type="button" class="remove-material text-red-500 hover:text-red-700" onclick="removeMaterial(this)">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(row);
            materialIndex++;
        });

        function removeMaterial(button) {
            const rows = document.querySelectorAll('.material-row');
            if (rows.length > 1) {
                button.closest('.material-row').remove();
            }
        }
    </script>
@endsection

