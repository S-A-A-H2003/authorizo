@php
    $navLinks = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'active' => request()->routeIs('admin.dashboard')],
        ['route' => 'admin.role.view', 'label' => 'Roles', 'active' => request()->routeIs('admin.role.*')],
        ['route' => 'admin.assign', 'label' => 'Assign Roles', 'active' => request()->routeIs('admin.assign*')],
    ];
@endphp

<nav class="main-nav">
    @foreach ($navLinks as $link)
        <a href="{{ route($link['route']) }}" class="{{ $link['active'] ? 'active' : '' }}">
            {{ $link['label'] }}
        </a>
    @endforeach
</nav>
