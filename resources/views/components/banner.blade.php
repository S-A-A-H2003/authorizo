@props(['type' => 'danger'])

<div {{ $attributes->merge(['class' => 'banner banner-' . $type]) }}>
    {{ $slot }}
</div>
