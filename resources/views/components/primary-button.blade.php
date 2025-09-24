<button {{ $attributes->merge(['type' => 'submit', 'class' => 'mx-auto px-10 py-2 bg-[#121212] rounded-2xl font-semibold text-xs text-white uppercase tracking-widest']) }}>
    {{ $slot }}
</button>