@props([
    'href',
    'icon' => null,
    'title',
    'description' => null,
    'meta' => null,
])

<a href="{{ $href }}" class="dash-card">
    @if ($icon)
        <div class="dash-card-icon">{{ $icon }}</div>
    @endif

    <div class="dash-card-body">
        <h3>{{ $title }}</h3>

        @if ($description)
            <p>{{ $description }}</p>
        @endif
    </div>

    @if ($meta)
        <div class="dash-card-meta">{{ $meta }} →</div>
    @endif
</a>
