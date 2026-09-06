@extends('authorizo::layouts.app')

@section('title', 'Roles · Authorizo')
@section('page-title', 'Roles')

@section('content')

    <x-flash-banners />

    <x-search-toolbar
        placeholder="Search roles..."
        :create-href="route('admin.role.create')"
        create-label="+ Create Role"
    />

    <div class="panel">
        @if ($roles->count())

            <table>
                <thead>
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Role</th>
                        <th>Slug</th>
                        <th style="width: 190px">Actions</th>
                    </tr>
                </thead>

                <tbody id="rolesBody">
                    @foreach ($roles as $role)
                        <tr
                            class="role-row"
                            data-name="{{ mb_strtolower($role->name) }}"
                            data-slug="{{ mb_strtolower($role->slug) }}"
                        >
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <x-chip :label="$role->name" :seed="$role->name" />
                            </td>

                            <td>
                                <code>{{ $role->slug }}</code>
                            </td>

                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('admin.role.edit', $role) }}" class="btn-action">Edit</a>

                                    <form
                                        action="{{ route('admin.role.delete', $role) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this role?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-action btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="empty-state" id="noMatches" style="display:none">No roles match your search.</div>

        @else

            <div class="empty-state">
                No roles found.
                <br><br>
                <a href="{{ route('admin.role.create') }}" class="btn-create">Create your first role</a>
            </div>

        @endif
    </div>

@endsection

@push('scripts')
    <x-table-filter-script
        row-selector=".role-row"
        :fields="['name', 'slug']"
        item-label="roles"
    />

    <x-banner-autodismiss-script />
@endpush
