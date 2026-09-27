@props([
    'type' => 'info', // success, danger, warning, info
    'title' => null,
])

@php
    $styles = [
        'success' => 'bg-[#E7F4EA] border-[#039834] text-[#0E6026]',
        'info' => 'bg-[#E7F4EA] border-[#039834] text-[#0E6026]',
        'danger' => 'bg-[#FBEAEA] border-[#C81210] text-[#C81210]',
        'warning' => 'bg-[#FBF9D6] border-[#E9E920] text-[#6B6200]',
    ];

    $class = $styles[$type] ?? $styles['info'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg border-l-4 p-4 ' . $class]) }} role="alert">
    @if ($title)
        <div class="font-semibold text-sm mb-1">{{ $title }}</div>
    @endif
    <div class="text-sm">
        {{ $slot }}
    </div>
</div>
