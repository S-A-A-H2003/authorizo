<?php

declare(strict_types=1);

use Authorizo\Authorizo\Controllers\AssignRoleController;
use Authorizo\Authorizo\Controllers\DashboardController;
use Authorizo\Authorizo\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

$prefix = (string) config('authorizo.routes.prefix', 'admin');
$middleware = config('authorizo.routes.middleware', ['web', 'authorizo']);
$name = (string) config('authorizo.routes.name', 'admin.');

if (! is_array($middleware)) {
    $middleware = ['web', 'authorizo'];
}

Route::prefix($prefix)->middleware($middleware)->name($name)->group(function () {
    Route::get('/authorizo', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/assign', [AssignRoleController::class, 'view'])->name('assign');
    Route::put('/assign/{user}', [AssignRoleController::class, 'update'])->name('assign.update');

    Route::prefix('role')->name('role.')->group(function () {
        Route::get('/', [RoleController::class, 'view'])->name('view');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}/update', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}/destroy', [RoleController::class, 'delete'])->name('delete');
    });
});
