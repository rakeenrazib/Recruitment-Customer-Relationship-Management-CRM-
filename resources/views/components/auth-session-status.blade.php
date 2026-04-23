@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-[1.15rem] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700']) }}>
        {{ $status }}
    </div>
@endif
