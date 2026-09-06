@extends('authorizo::layouts.app')

@section('title', 'Create Role · Authorizo')
@section('page-title', 'Create Role')

@section('content')

    <x-flash-banners />

    <x-panel title="Create a new role" description="Add a role that can later be assigned to users.">
        <form action="{{ route('admin.role.store') }}" method="POST">
            @csrf

            <x-field name="name" label="Role name" placeholder="Administrator" required />

            <x-field
                name="slug"
                label="Role slug"
                placeholder="admin"
                required
                hint="The slug must be unique."
            />

            <x-form-actions :cancel-href="route('admin.role.view')" submit-label="Create Role" />
        </form>
    </x-panel>

@endsection
