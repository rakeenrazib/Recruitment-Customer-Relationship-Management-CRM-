<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary text-xs uppercase tracking-[0.22em] disabled:opacity-25']) }}>
    {{ $slot }}
</button>
