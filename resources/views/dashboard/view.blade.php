@extends('authorizo::layouts.app')

@section('title', 'Dashboard · Authorizo')
@section('page-title', 'Dashboard')

@section('content')

    <x-authorizo::flash-banners />

    <div class="dash-hero">
        <h2>Welcome back</h2>
        <p>Manage roles and assign them to your users from one place.</p>
    </div>

    <div class="dash-grid">

        <x-authorizo::dashboard-card
            href="{{ route('admin.role.view') }}"
            icon="🛡️"
            title="Roles"
            description="Create, edit, and delete roles and their permissions."
            :meta="isset($rolesCount) ? $rolesCount . ' roles' : 'Manage roles'"
        />

        <x-authorizo::dashboard-card
            href="{{ route('admin.assign') }}"
            icon="👥"
            title="Assign Roles"
            description="Search your users and assign them a role."
            :meta="isset($usersCount) ? $usersCount . ' users' : 'Manage assignments'"
        />

        <x-authorizo::dashboard-card
            href="{{ route('admin.role.create') }}"
            icon="+"
            title="Create Role"
            description="Add a new role that can be assigned to users."
            meta="Quick action"
        />

    </div>

@endsection
