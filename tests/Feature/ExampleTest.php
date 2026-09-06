<?php

declare(strict_types=1);

use Authorizo\Authorizo\Authorizo;
use Authorizo\Authorizo\Models\Permission;
use Authorizo\Authorizo\Models\Role;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Workbench\App\Models\User;

it('resolves the authorizo singleton', function (): void {
    expect(app(Authorizo::class))->toBeInstanceOf(Authorizo::class)
        ->and(app(Authorizo::class))->toBe(app(Authorizo::class));
});

it('merges the package config', function (): void {
    expect(config('authorizo.routes.prefix'))->toBe('admin')
        ->and(config('authorizo.roles.admin'))->toBe('admin')
        ->and(config('authorizo.roles.user'))->toBe('user');
});

it('loads package views and admin routes', function (): void {
    expect(view()->exists('authorizo::dashboard.view'))->toBeTrue()
        ->and(view()->exists('authorizo::role.view'))->toBeTrue()
        ->and(view()->exists('authorizo::assign.view'))->toBeTrue()
        ->and(Route::has('admin.dashboard'))->toBeTrue()
        ->and(Route::has('admin.role.view'))->toBeTrue()
        ->and(Route::has('admin.assign'))->toBeTrue();
});

it('installs permissions and default roles for authorizo routes', function (): void {
    Route::get('/posts', [PostController::class, 'index'])->middleware('authorizo');

    $this->artisan('authorizo:install')->assertSuccessful();

    $permission = Permission::query()->where('slug', 'post.index')->first();
    $admin = Role::query()->where('slug', 'admin')->first();
    $user = Role::query()->where('slug', 'user')->first();

    expect($permission)->not->toBeNull()
        ->and($admin)->not->toBeNull()
        ->and($user)->not->toBeNull()
        ->and((bool) $admin?->permissions()->where('slug', 'post.index')->value('allowed'))->toBeTrue()
        ->and((bool) $user?->permissions()->where('slug', 'post.index')->value('allowed'))->toBeFalse();
});

it('allows users when their role has the route permission', function (): void {
    Route::get('/allowed-posts', [PostController::class, 'index'])->middleware(['web', 'auth', 'authorizo']);

    $role = Role::query()->create(['name' => 'Editor', 'slug' => 'editor']);
    $permission = Permission::query()->create([
        'controller' => 'Post',
        'action' => 'index',
        'name' => 'Post index',
        'slug' => 'post.index',
    ]);
    $role->permissions()->attach($permission, ['allowed' => true]);

    $user = User::query()->create([
        'name' => 'Editor',
        'email' => 'editor@example.com',
        'password' => 'password',
        'role_id' => $role->id,
    ]);

    $this->actingAs($user)
        ->get('/allowed-posts')
        ->assertOk()
        ->assertSee('ok');
});

it('denies users when their role does not have the route permission', function (): void {
    Route::get('/denied-posts', [PostController::class, 'index'])->middleware(['web', 'auth', 'authorizo']);

    $role = Role::query()->create(['name' => 'Viewer', 'slug' => 'viewer']);
    $permission = Permission::query()->create([
        'controller' => 'Post',
        'action' => 'index',
        'name' => 'Post index',
        'slug' => 'post.index',
    ]);
    $role->permissions()->attach($permission, ['allowed' => false]);

    $user = User::query()->create([
        'name' => 'Viewer',
        'email' => 'viewer@example.com',
        'password' => 'password',
        'role_id' => $role->id,
    ]);

    $this->actingAs($user)
        ->get('/denied-posts')
        ->assertNotFound();
});

class PostController
{
    public function index(): Response
    {
        return response('ok');
    }
}
