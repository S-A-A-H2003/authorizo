@props([
    'placeholder' => 'Search...',
    'createHref' => null,
    'createLabel' => '+ Create',
    'roles' => null,
])

<div class="toolbar">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="{{ $placeholder }}" autocomplete="off">
    </div>

    @if ($roles)
        <select id="roleFilter" class="role-filter">
            <option value="">All roles</option>

            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    @endif

    @if ($createHref)
        <a href="{{ $createHref }}" class="btn-create">{{ $createLabel }}</a>
    @endif
</div>

<div class="result-count" id="resultCount"></div>
