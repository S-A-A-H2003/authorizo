@extends('authorizo::layouts.app')

@section('title', 'Edit Role · Authorizo')
@section('page-title', 'Edit Role')

@section('content')

    <x-flash-banners />

    <x-panel
        title="Edit role: {{ $role->name }}"
        description="Update the role details and its assigned permissions."
    >
        <form action="{{ route('admin.role.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <x-field name="name" label="Role name" :value="$role->name" placeholder="Administrator" required />

            <x-field
                name="slug"
                label="Role slug"
                :value="$role->slug"
                placeholder="admin"
                required
                hint="The slug must be unique."
            />

            @php
                // "allowed" pivot column drives which checkboxes start checked
                $allowedPermissionIds = $role->permissions
                    ->filter(fn ($permission) => (bool) $permission->pivot->allowed)
                    ->pluck('id')
                    ->toArray();

                $checkedPermissionIds = old('permissions', $allowedPermissionIds);
            @endphp

            <x-permissions-grid :permissions="$permissions" :checked-ids="$checkedPermissionIds" />

            <x-form-actions :cancel-href="route('admin.role.view')" submit-label="Save Changes" />
        </form>
    </x-panel>

@endsection
