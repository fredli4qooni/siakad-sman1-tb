@props([
    'disabled' => false,
    'label' => null,
    'name' => null,
    'type' => 'text',
    'id' => null,
    'help' => null,
    'error' => null,
    'value' => null,
    'required' => false,
])

@php
    $inputId = $id ?? $name ?? 'input-' . uniqid();
    $hasError = $error || ($name && $errors->has($name));
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);
@endphp

<div class="w-full">
    @if ($label)
        <label for="{{ $inputId }}" class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-[#C81210]">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $inputId }}"
        value="{{ old($name, $value) }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border px-3.5 py-2 text-sm text-[#1C2620] placeholder-[#9FA29E] bg-white transition-colors duration-150 focus:outline-none ' . 
            ($hasError 
                ? 'border-[#C81210] focus:border-[#C81210] focus:ring-1 focus:ring-[#C81210]' 
                : 'border-[#C9CDC3] focus:border-[#039834] focus:ring-1 focus:ring-[#039834]') .
            ($disabled ? ' bg-[#F3F5F2] cursor-not-allowed text-[#9FA29E]' : '')
        ]) }}
    />

    @if ($help && !$hasError)
        <p class="mt-1 text-xs text-[#545B52]">{{ $help }}</p>
    @endif

    @if ($hasError)
        <p class="mt-1 text-xs text-[#C81210] flex items-center gap-1 font-medium">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
            </svg>
            <span>{{ $errorMessage }}</span>
        </p>
    @endif
</div>
