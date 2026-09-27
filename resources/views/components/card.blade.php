@props([
    'title' => null,
    'subtitle' => null,
    'action' => null,
    'padding' => 'p-5 sm:p-6',
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-[#E1E4DE] ' . $padding]) }}>
    @if ($title || $subtitle || $action)
        <div class="flex items-start justify-between gap-4 pb-4 mb-5 border-b border-[#E1E4DE]">
            <div>
                @if ($title)
                    <h3 class="text-lg font-semibold text-[#1C2620] leading-snug">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="mt-0.5 text-xs text-[#545B52]">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($action)
                <div class="flex-shrink-0">
                    {{ $action }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
