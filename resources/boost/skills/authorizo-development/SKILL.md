---
name: authorizo-development
description: >
  Configure and apply the Authorizo package in Laravel applications.
license: MIT
metadata:
  author: saeedAZ19
---

# Authorizo

Use this skill when a Laravel application needs to integrate the Authorizo package.

## Primary Goal

- install and apply `saeedaz19/authorizo` for role-based permission checks in a Laravel application

## Workflow

### 1. Inspect the Laravel app context

- confirm the app is a Laravel project
- confirm the app uses PHP 8.3+ and Laravel 12 or 13
- identify the authenticatable user model from `config/auth.php`
- identify the routes/controllers that should be protected by Authorizo

### 2. Apply the package's public API

- install the package with `composer require saeedaz19/authorizo`
- publish and run migrations with `php artisan vendor:publish --tag="authorizo-migrations"` and `php artisan migrate`
- publish config with `php artisan vendor:publish --tag="authorizo-config"` when the app needs a custom route prefix, route middleware, route name prefix, or role slugs
- add `Authorizo\Authorizo\Traits\HasAuthorizo` to the application's user model
- protect controller routes with the registered `authorizo` middleware, usually alongside `web` and `auth`
- run `php artisan authorizo:install` after the protected routes are registered
- visit `/admin/authorizo`, `/admin/role`, or `/admin/assign` to manage roles and assignments

### 3. Publish resources only when needed

- use `php artisan vendor:publish --tag="authorizo-config"` for configuration
- use `php artisan vendor:publish --tag="authorizo-views"` to customize views
- use `php artisan vendor:publish --tag="authorizo-lang"` to customize translations
- use `php artisan vendor:publish --tag="authorizo"` to publish all package resources

## Rules, References, and Templates

Read before executing:

- README.md
- config/authorizo.php
- routes/authorizo.php
- src/Traits/HasAuthorizo.php
- src/Middleware/AuthorizoMiddleware.php
- src/Console/Commands/AuthorizoCommand.php

## Examples

- protect resource-like controller routes:

```php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'authorizo'])->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
});
```

- for `PostController@index`, Authorizo checks the `post.index` permission slug

## Anti-patterns

- do not document package internals here; keep the skill focused on adoption in Laravel apps
- do not run `authorizo:install` before adding the routes that should become permissions
- do not protect closure routes with `authorizo`; permissions are generated from controller and action names
- do not assume Authorizo creates application users; assign an existing user to an allowed role after installation
