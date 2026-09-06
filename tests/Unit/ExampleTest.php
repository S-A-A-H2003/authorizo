<?php

declare(strict_types=1);

use Authorizo\Authorizo\Models\Role;
use Workbench\App\Models\User;

it('assigns the default user role when a user is created', function (): void {
    $role = Role::query()->create(['name' => 'User', 'slug' => 'user']);

    $user = User::query()->create([
        'name' => 'New User',
        'email' => 'new-user@example.com',
        'password' => 'password',
    ]);

    expect($user->role_id)->toBe($role->id);
});
