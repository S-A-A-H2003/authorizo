@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'panel panel--padded']) }}>
    @if ($title)
        <div class="panel-header">
            <h2>{{ $title }}</h2>

            @if ($description)
                <p>{{ $description }}</p>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
