@props([
    'name',
    'label',
    'value' => null,
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'hint' => null,
])

<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>

    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        {{ $attributes }}
    >

    @if ($hint)
        <small>{{ $hint }}</small>
    @endif

    @error($name)
        <small style="color: var(--danger);">{{ $message }}</small>
    @enderror
</div>
