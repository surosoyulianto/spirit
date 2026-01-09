<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manufacturing Order Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $manufacturing->mo_number }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Created: {{ $manufacturing->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $manufacturing->statusBadge }}">
                            {{ ucfirst(str_replace('_', ' ', $manufacturing->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label :value="__('Product')" />
                            <p class="text-lg font-medium text-gray-900 dark:text-white mt-1">
                                {{ $manufacturing->product->name }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $manufacturing->product->sku }}</p>
                        </div>
                        <div>
                            <x-input-label :value="__('Quantity')" />
                            <p class="text-lg font-medium text-gray-900 dark:text-white mt-1">
                                {{ $manufacturing->quantity }} units
                            </p>
                        </div>
                        <div>
                            <x-input-label :value="__('Scheduled Date')" />
                            <p class="text-gray-900 dark:text-white mt-1">
                                {{ $manufacturing->scheduled_date ?: 'Not set' }}
                            </p>
                        </div>
                        <div>
                            <x-input-label :value="__('Completed Date')" />
                            <p class="text-gray-900 dark:text-white mt-1">
                                {{ $manufacturing->completed_date ?: 'In progress' }}
                            </p>
                        </div>
                    </div>

                    @if($manufacturing->notes)
                    <div class="mb-6">
                        <x-input-label :value="__('Notes')" />
                        <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $manufacturing->notes }}</p>
                    </div>
                    @endif

                    <!-- Status Workflow -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('Workflow Actions') }}
                        </h4>
                        
                        @if(count($manufacturing->getAvailableTransitions()) > 0)
                            <form action="{{ route('manufacturing.updateStatus', $manufacturing) }}" method="POST" class="flex gap-4 items-center">
                                @csrf
                                @method('POST')
                                <div>
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
                                    <x-primary-button type="submit">
                                        {{ __('Update Status') }}
                                    </x-primary-button>
                                </div>
                            </form>
                            
                            @if(in_array('done', $manufacturing->getAvailableTransitions()))
                                <div class="mt-4 p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                                    <p class="text-sm text-green-700 dark:text-green-300">
                                        <strong>Note:</strong> When status changes to "Done", {{ $manufacturing->quantity }} units will be added to the product stock automatically.
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
                            {{ __('Inventory Impact') }}
                        </h4>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Type') }}</th>
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
                                            <td class="px-4 py-3 text-sm font-medium {{ $inventory->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $inventory->type === 'in' ? '+' : '-' }}{{ $inventory->quantity }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $inventory->reason }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
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
