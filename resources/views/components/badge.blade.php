@props(['type' => 'default', 'label'])

@php
    $colors = [
        'in progress' => 'bg-blue-100 text-blue-700',
        'done' => 'bg-green-100 text-green-700',
        'waiting' => 'bg-yellow-100 text-yellow-700',
        'default' => 'bg-gray-100 text-gray-700',
    ];
    $class = $colors[strtolower($type)] ?? $colors['default'];
@endphp

<span class="px-2 py-1 text-xs font-semibold rounded {{ $class }}">
    {{ ucfirst($label) }}
</span>
