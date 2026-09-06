@props([
    'variant' => 'secondary',
    'href' => null,
    'type' => 'button',
])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn btn-' . $variant]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn-' . $variant]) }}>
        {{ $slot }}
    </button>
@endif
