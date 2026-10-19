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
    $isPassword = ($type === 'password');
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

    <div class="relative">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ old($name, $value) }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full rounded-lg border px-3.5 py-2 text-sm text-[#1C2620] placeholder-[#9FA29E] bg-white transition-colors duration-150 focus:outline-none ' . 
                ($isPassword ? 'pr-10 ' : '') .
                ($hasError 
                    ? 'border-[#C81210] focus:border-[#C81210] focus:ring-1 focus:ring-[#C81210]' 
                    : 'border-[#C9CDC3] focus:border-[#039834] focus:ring-1 focus:ring-[#039834]') .
                ($disabled ? ' bg-[#F3F5F2] cursor-not-allowed text-[#9FA29E]' : '')
            ]) }}
        />

        @if ($isPassword)
            <button
                type="button"
                tabindex="-1"
                onclick="const inp = document.getElementById('{{ $inputId }}'); if(inp){ const isPwd = inp.type === 'password'; inp.type = isPwd ? 'text' : 'password'; this.querySelector('.eye-open').classList.toggle('hidden', !isPwd); this.querySelector('.eye-closed').classList.toggle('hidden', isPwd); }"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#545B52] hover:text-[#0E6026] focus:outline-none cursor-pointer transition-colors"
                title="Tampilkan / Sembunyikan Kata Sandi"
                aria-label="Tampilkan / Sembunyikan Kata Sandi"
            >
                <!-- Eye Open (shown when password revealed) -->
                <svg class="eye-open w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <!-- Eye Closed / Slash (shown when password hidden) -->
                <svg class="eye-closed w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        @endif
    </div>

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
