@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-[1rem] px-4 py-3 text-start text-sm font-semibold text-slate-950 bg-white shadow-sm transition duration-200'
            : 'block w-full rounded-[1rem] px-4 py-3 text-start text-sm font-semibold text-slate-600 transition duration-200 hover:bg-white/70 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
