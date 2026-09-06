@extends('authorizo::layouts.app')

@section('title', 'Assign Roles · Authorizo')
@section('page-title', 'Assign Roles')

@section('content')

    <x-flash-banners />

    <x-search-toolbar placeholder="Search by name or email..." :roles="$roles" />

    <div class="panel">
        <table>
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>User</th>
                    <th style="width:170px">Current Role</th>
                    <th style="width:230px">Assign New Role</th>
                </tr>
            </thead>

            <tbody id="usersBody">
                @forelse ($users as $user)
                    @php
                        $currentRole = $user->role instanceof \Authorizo\Authorizo\Models\Role
                            ? $user->role
                            : $roles->firstWhere('id', $user->role);

                        $avatarHue = crc32($user->email) % 360;
                        $initials = collect(explode(' ', trim($user->name)))
                            ->map(fn ($p) => mb_substr($p, 0, 1))
                            ->take(2)
                            ->join('');
                    @endphp

                    <tr
                        class="user-row"
                        data-name="{{ mb_strtolower($user->name) }}"
                        data-email="{{ mb_strtolower($user->email) }}"
                        data-role-id="{{ $currentRole->id ?? '' }}"
                    >
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="background: hsl({{ $avatarHue }}, 55%, 45%)">
                                    {{ mb_strtoupper($initials) ?: '?' }}
                                </div>

                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if ($currentRole)
                                <x-chip :label="$currentRole->name" :seed="$currentRole->name" />
                            @else
                                <x-chip label="No role" empty />
                            @endif
                        </td>

                        <td>
                            <form
                                action="{{ route('admin.assign.update', $user) }}"
                                method="POST"
                                class="assign-form"
                                data-role-form
                            >
                                @csrf
                                @method('PUT')

                                <select name="role" class="role-select" data-original="{{ $currentRole->id ?? '' }}" required>
                                    <option value="" disabled {{ $currentRole ? '' : 'selected' }}>Select</option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ $currentRole && $currentRole->id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn-save" disabled>Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"><div class="empty-state">No users found.</div></td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="empty-state" id="noMatches" style="display:none">No users match your search.</div>
    </div>

@endsection

@push('scripts')
    <x-table-filter-script
        row-selector=".user-row"
        :fields="['name', 'email']"
        role-filter
        item-label="users"
    />

    <script>
        document.querySelectorAll('[data-role-form]').forEach((form) => {
            const select = form.querySelector('select[name="role"]');
            const button = form.querySelector('.btn-save');
            const original = select.dataset.original;

            select.addEventListener('change', () => {
                button.disabled = select.value === original;
            });

            form.addEventListener('submit', () => {
                button.disabled = true;
                button.textContent = 'Saving...';
            });
        });
    </script>

    <x-banner-autodismiss-script />
@endpush
