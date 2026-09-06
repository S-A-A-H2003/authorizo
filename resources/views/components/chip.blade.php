@props([
    'label',
    'seed' => null,
    'empty' => false,
])

@if ($empty)
    <span class="chip chip-empty">{{ $label }}</span>
@else
    @php $hue = crc32($seed ?? $label) % 360; @endphp

    <span class="chip">
        <span class="chip-dot" style="background: hsl({{ $hue }}, 65%, 50%)"></span>
        {{ $label }}
    </span>
@endif
