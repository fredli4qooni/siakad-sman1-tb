@props([
    'status' => 'draft', // lulus, diterima, ditolak, menunggu, draft, aktif, nonaktif
])

@php
    $normalized = strtolower(trim($status));
    
    $styles = [
        'lulus' => 'bg-[#E7F4EA] text-[#0E6026] border border-[#0E6026]/10',
        'diterima' => 'bg-[#E7F4EA] text-[#0E6026] border border-[#0E6026]/10',
        'aktif' => 'bg-[#E7F4EA] text-[#0E6026] border border-[#0E6026]/10',
        'ditolak' => 'bg-[#FBEAEA] text-[#C81210] border border-[#C81210]/10',
        'tidak_lulus' => 'bg-[#FBEAEA] text-[#C81210] border border-[#C81210]/10',
        'nonaktif' => 'bg-[#FBEAEA] text-[#C81210] border border-[#C81210]/10',
        'menunggu' => 'bg-[#FBF9D6] text-[#6B6200] border border-[#6B6200]/10',
        'cadangan' => 'bg-[#FBF9D6] text-[#6B6200] border border-[#6B6200]/10',
        'draft' => 'bg-[#F3F5F2] text-[#545B52] border border-[#C9CDC3]',
    ];

    $class = $styles[$normalized] ?? $styles['draft'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $class]) }}>
    {{ $slot }}
</span>
