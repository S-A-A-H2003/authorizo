<?php

declare(strict_types=1);

namespace Authorizo\Authorizo\Controllers;

use Authorizo\Authorizo\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RuntimeException;

class AssignRoleController extends Controller
{
    public function view(Request $request): View
    {
        $users = $this->userModel()->newQuery()->get();
        $roles = Role::query()->get();

        return view('authorizo::assign.view', compact('users', 'roles'));
    }

    public function update(Request $request, int|string $user): RedirectResponse
    {
        $userRecord = $this->userModel()->newQuery()->findOrFail($user);

        $request->validate([
            'role' => ['required', 'exists:'.(new Role)->getTable().',id'],
        ]);

        if ((int) $userRecord->getKey() === 1) {
            return redirect()->back()->with('error', 'The primary admin\'s role cannot be changed.');
        }

        $userRecord->update(['role_id' => $request->input('role')]);

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    private function userModel(): Model
    {
        $userModel = config('auth.providers.users.model');

        if (! is_string($userModel) || ! is_subclass_of($userModel, Model::class)) {
            throw new RuntimeException('Authorizo requires an Eloquent user model.');
        }

        return new $userModel;
    }
}
