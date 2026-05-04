@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold leading-5 text-white shadow-lg shadow-slate-950/10 transition duration-200'
            : 'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold leading-5 text-slate-500 transition duration-200 hover:bg-white/70 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
