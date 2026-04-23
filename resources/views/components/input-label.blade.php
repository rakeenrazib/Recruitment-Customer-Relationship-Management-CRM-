@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-2 block text-xs font-bold uppercase tracking-[0.24em] text-slate-400']) }}>
    {{ $value ?? $slot }}
</label>
