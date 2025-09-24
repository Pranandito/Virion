@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-gray-800']) }}>
    {{ $value ?? $slot }}
</label>