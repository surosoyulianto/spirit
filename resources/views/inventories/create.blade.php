<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Inventory Movement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('inventories.store') }}">
                        @csrf

                        <div class="mb-6">
                            <x-input-label for="product_id" :value="__('Product')" />
                            <select name="product_id" id="product_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="type" :value="__('Movement Type')" />
                            <select name="type" id="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>In (Stock Increase)</option>
                                <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Out (Stock Decrease)</option>
                                <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="quantity" :value="__('Quantity')" />
                            <x-text-input id="quantity" name="quantity" type="number" class="mt-1 block w-full" :value="old('quantity', 1)" min="1" required />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="reason" :value="__('Reason')" />
                            <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full" :value="old('reason')" placeholder="e.g., Stock received from supplier, Damaged goods, etc." required />
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('inventories.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Record Movement') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
