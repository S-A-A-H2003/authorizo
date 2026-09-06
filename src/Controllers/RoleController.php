<?php

declare(strict_types=1);

namespace Authorizo\Authorizo\Controllers;

use Authorizo\Authorizo\Models\Permission;
use Authorizo\Authorizo\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class RoleController extends Controller
{
    public function view(): View
    {
        $roles = Role::query()->get();

        return view('authorizo::role.view', compact('roles'));
    }

    public function create(): View
    {
        return view('authorizo::role.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required'],
            'slug' => ['required', 'unique:'.(new Role)->getTable().',slug'],
        ]);

        try {
            $role = Role::query()->create($validated);
            $permissionIds = Permission::query()->pluck('id');

            $role->permissions()->syncWithPivotValues($permissionIds, ['allowed' => false]);
        } catch (Throwable) {
            return redirect()->back()->with('error', 'Failed to create role.');
        }

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    public function edit(Request $request, Role $role): View|RedirectResponse
    {
        if ($role->slug === config('authorizo.roles.admin', 'admin')) {
            return redirect()->back()->with('error', 'The admin role cannot be modified.');
        }

        $permissions = Permission::query()->get();

        return view('authorizo::role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->slug === config('authorizo.roles.admin', 'admin')) {
            return redirect()->back()->with('error', 'The admin role cannot be modified.');
        }

        $validated = $request->validate([
            'name' => ['required'],
            'slug' => ['required', Rule::unique((new Role)->getTable(), 'slug')->ignore($role->id)],
            'permissions' => ['array'],
            'permissions.*' => ['exists:'.(new Permission)->getTable().',id'],
        ]);

        $checkedPermissionIds = array_map('intval', $validated['permissions'] ?? []);

        try {
            DB::transaction(function () use ($role, $validated, $checkedPermissionIds): void {
                $role->update([
                    'name' => $validated['name'],
                    'slug' => $validated['slug'],
                ]);

                $syncData = Permission::query()
                    ->pluck('id')
                    ->mapWithKeys(function (int|string $id) use ($checkedPermissionIds): array {
                        $permissionId = (int) $id;

                        return [$permissionId => ['allowed' => in_array($permissionId, $checkedPermissionIds, true)]];
                    });

                $role->permissions()->sync($syncData);
            });
        } catch (Throwable) {
            return redirect()->back()->with('error', 'Failed to update role.');
        }

        return redirect()->back()->with('success', 'Role updated successfully.');
    }

    public function delete(Role $role): RedirectResponse
    {
        $protectedRoles = [
            config('authorizo.roles.admin', 'admin'),
            config('authorizo.roles.user', 'user'),
        ];

        if (in_array($role->slug, $protectedRoles, true)) {
            return redirect()->back()->with('error', 'The admin and user role cannot be deleted.');
        }

        try {
            $role->delete();
        } catch (Throwable) {
            return redirect()->back()->with('error', 'Failed to delete role.');
        }

        return redirect()->back()->with('success', 'Role deleted successfully.');
    }
}
