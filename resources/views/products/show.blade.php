<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">{{ $product->name }}</h3>
                        <div>
                            <a href="{{ route('products.edit', $product) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Image -->
                        @if($product->image)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Image</h4>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
                        </div>
                        @endif

                        <!-- Product Details -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Name</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->name }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">SKU</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->sku ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Category</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->category ?: 'N/A' }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Price</h4>
                                <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Stock</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->stock }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Created At</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('d M Y H:i') }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Updated At</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($product->description)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500">Description</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $product->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
