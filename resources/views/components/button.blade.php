@props([
    'variant' => 'primary', // primary, secondary, danger, ghost
    'type' => 'button',
    'as' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center text-sm font-medium px-4 py-2 rounded-lg transition-colors duration-150 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-[#0E6026] hover:bg-[#0A4C1E] text-white focus:ring-2 focus:ring-[#039834] focus:ring-offset-1',
        'secondary' => 'bg-white hover:bg-[#F3F5F2] text-[#1C2620] border border-[#C9CDC3] focus:ring-2 focus:ring-[#C9CDC3]',
        'danger' => 'bg-[#C81210] hover:bg-[#A30E0C] text-white focus:ring-2 focus:ring-[#F10704] focus:ring-offset-1',
        'ghost' => 'text-[#0E6026] hover:bg-[#E7F4EA] px-3 py-1.5',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($as === 'a' || $href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
