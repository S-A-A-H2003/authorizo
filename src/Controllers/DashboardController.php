<?php

declare(strict_types=1);

namespace Authorizo\Authorizo\Controllers;

use Authorizo\Authorizo\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class DashboardController
{
    public function index(): View
    {
        $userModel = config('auth.providers.users.model');
        $usersCount = null;

        if (is_string($userModel) && is_subclass_of($userModel, Model::class)) {
            $usersCount = (new $userModel)->newQuery()->count();
        }

        return view('authorizo::dashboard.view', [
            'rolesCount' => Role::count(),
            'usersCount' => $usersCount,
        ]);
    }
}
