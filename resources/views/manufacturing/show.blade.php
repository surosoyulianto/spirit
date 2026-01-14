<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manufacturing Order Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $manufacturing->mo_number }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Created:') }} {{ $manufacturing->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $manufacturing->statusBadge }}">
                            {{ ucfirst(str_replace('_', ' ', $manufacturing->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Finished Product -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <x-input-label :value="__('Finished Product')" />
                            <p class="text-lg font-medium text-gray-900 dark:text-white mt-1">
                                {{ $manufacturing->product->name }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $manufacturing->product->sku }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('Current Stock:') }} {{ $manufacturing->product->stock }}
                            </p>
                        </div>

                        <!-- Quantity -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <x-input-label :value="__('Quantity to Produce')" />
                            <p class="text-lg font-medium text-gray-900 dark:text-white mt-1">
                                {{ number_format($manufacturing->quantity) }} {{ __('units') }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('Finished goods value:') }} Rp {{ number_format($manufacturing->product->price * $manufacturing->quantity, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Dates -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <x-input-label :value="__('Dates')" />
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                <span class="font-medium">{{ __('Scheduled:') }}</span> {{ $manufacturing->scheduled_date ?: __('Not set') }}
                            </p>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                <span class="font-medium">{{ __('Completed:') }}</span> {{ $manufacturing->completed_date ?: __('In progress') }}
                            </p>
                        </div>
                    </div>

                    @if($manufacturing->notes)
                    <div class="mb-6">
                        <x-input-label :value="__('Notes')" />
                        <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $manufacturing->notes }}</p>
                    </div>
                    @endif

                    <!-- Materials Used -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('Raw Materials Required') }}
                        </h4>

                        @if($manufacturing->materials->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Product') }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('SKU') }}</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Qty per Unit') }}</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Total Qty') }}</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Current Stock') }}</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Unit Price') }}</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Subtotal') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @php $totalMaterialCost = 0; @endphp
                                        @foreach($manufacturing->materials as $material)
                                            @php
                                                $qtyPerUnit = $material->pivot->quantity;
                                                $totalQty = $qtyPerUnit * $manufacturing->quantity;
                                                $subtotal = $material->price * $totalQty;
                                                $totalMaterialCost += $subtotal;
                                                $isLowStock = $material->stock < $totalQty;
                                            @endphp
                                            <tr class="{{ $isLowStock ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $material->name }}
                                                    @if($isLowStock)
                                                        <span class="ml-2 text-xs text-red-600 dark:text-red-400">({{ __('Insufficient') }})</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $material->sku }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center text-gray-900 dark:text-white">
                                                    {{ $qtyPerUnit }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center font-medium {{ $isLowStock ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                                    {{ $totalQty }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center {{ $isLowStock ? 'text-red-600 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                                    {{ $material->stock }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-right text-gray-500 dark:text-gray-400">
                                                    Rp {{ number_format($material->price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-white">
                                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-gray-100 dark:bg-gray-700">
                                            <td colspan="6" class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white text-right">
                                                {{ __('Total Material Cost:') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white text-right">
                                                Rp {{ number_format($totalMaterialCost, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @if(in_array($manufacturing->status, ['draft', 'confirmed']))
                                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                        <strong>{{ __('Note:') }}</strong>
                                        @if($manufacturing->status === 'draft')
                                            {{ __('When you change status to "Confirmed", material requirements will be reserved. Stock will be deducted when production starts.') }}
                                        @else
                                            {{ __('When production starts, the raw material stock will be deducted automatically.') }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('No materials assigned') }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Add raw materials to this manufacturing order.') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Status Workflow -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('Workflow Actions') }}
                        </h4>

                        @php
                            $canProceed = true;
                            if (in_array($manufacturing->status, ['draft', 'confirmed'])) {
                                foreach ($manufacturing->materials as $material) {
                                    $required = $material->pivot->quantity * $manufacturing->quantity;
                                    if ($material->stock < $required) {
                                        $canProceed = false;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if(count($manufacturing->getAvailableTransitions()) > 0)
                            <form action="{{ route('manufacturing.updateStatus', $manufacturing) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                                @csrf
                                @method('POST')
                                <div class="flex-1 min-w-[200px]">
                                    <x-input-label for="status" :value="__('Change Status')" />
                                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @foreach($manufacturing->getAvailableTransitions() as $transition)
                                            <option value="{{ $transition }}">
                                                {{ ucfirst(str_replace('_', ' ', $transition)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-6">
                                    <x-primary-button type="submit" {{ !$canProceed ? 'disabled' : '' }}>
                                        {{ __('Update Status') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            @if(!$canProceed && in_array($manufacturing->status, ['draft', 'confirmed']))
                                <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <p class="text-sm text-red-700 dark:text-red-300">
                                        <strong>{{ __('Cannot start production:') }}</strong>
                                        {{ __('Insufficient raw material stock. Please check the materials table above.') }}
                                    </p>
                                </div>
                            @endif

                            @if(in_array('done', $manufacturing->getAvailableTransitions()))
                                <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <p class="text-sm text-green-700 dark:text-green-300">
                                        <strong>{{ __('Note:') }}</strong>
                                        {{ __('When status changes to "Done",') }}
                                        {{ number_format($manufacturing->quantity) }}
                                        {{ __('units will be added to the product stock automatically.') }}
                                    </p>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 dark:text-gray-400">
                                {{ __('No more status transitions available.') }}
                            </p>
                        @endif
                    </div>

                    <!-- Inventory Impact -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('Inventory Movements') }}
                        </h4>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Type') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Product') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Reason') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($manufacturing->inventories as $inventory)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                {{ $inventory->date }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $inventory->typeBadge }}">
                                                    {{ ucfirst($inventory->type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $inventory->product->name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium {{ $inventory->type === 'in' || $inventory->type === 'reserved' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $inventory->type === 'in' ? '+' : ($inventory->type === 'reserved' ? '±' : '-') }}{{ $inventory->quantity }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $inventory->reason }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                                {{ __('No inventory movements recorded for this order.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-6">
                <a href="{{ route('manufacturing.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                    &larr; {{ __('Back to Manufacturing Orders') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

