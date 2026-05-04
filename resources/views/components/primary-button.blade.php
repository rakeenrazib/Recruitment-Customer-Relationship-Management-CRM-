<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary text-xs uppercase tracking-[0.22em]']) }}>
    {{ $slot }}
</button>
