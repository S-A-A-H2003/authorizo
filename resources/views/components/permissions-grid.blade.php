@props([
    'permissions',
    'checkedIds' => [],
])

<div class="permissions-section">
    <label>Permissions</label>

    <div class="permissions-grid">
        @foreach ($permissions as $permission)
            <div class="permission-item">
                <input
                    type="checkbox"
                    name="permissions[]"
                    id="permission_{{ $permission->id }}"
                    value="{{ $permission->id }}"
                    {{ in_array($permission->id, $checkedIds) ? 'checked' : '' }}
                >

                <label for="permission_{{ $permission->id }}">
                    {{ $permission->name }}
                </label>
            </div>
        @endforeach
    </div>

    @error('permissions')
        <small style="color: var(--danger);">{{ $message }}</small>
    @enderror
</div>
