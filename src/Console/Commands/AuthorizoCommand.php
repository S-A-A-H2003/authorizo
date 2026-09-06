<?php

declare(strict_types=1);

namespace Authorizo\Authorizo\Console\Commands;

use Authorizo\Authorizo\Models\Permission;
use Authorizo\Authorizo\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Throwable;

class AuthorizoCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'authorizo:install';

    /**
     * The command description.
     */
    protected $description = 'Populate permissions and create the default roles';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Authorizo installation...');

        $processed = 0;
        $failed = 0;
        $skipped = 0;

        $routes = Route::getRoutes()->getRoutes();
        $bar = $this->output->createProgressBar(count($routes));
        $bar->start();

        foreach ($routes as $route) {
            $controllerClass = $route->getControllerClass();
            $usesAuthorizoMiddleware = in_array('authorizo', $route->middleware(), true);

            if ($controllerClass === null || ! $usesAuthorizoMiddleware) {
                $skipped++;
                $bar->advance();

                continue;
            }

            $controller = class_basename(Str::beforeLast($controllerClass, 'Controller'));
            $action = $route->getActionMethod();

            $permission = [
                'controller' => $controller,
                'action' => $action,
                'name' => ucwords($controller.' '.$action),
                'slug' => strtolower($controller.'.'.$action),
            ];

            try {
                DB::transaction(function () use ($permission): void {
                    Permission::query()->updateOrCreate(
                        ['slug' => $permission['slug']],
                        $permission,
                    );
                });

                $processed++;
            } catch (Throwable $throwable) {
                $failed++;
                $this->newLine();
                $this->error("Failed to create permission [{$permission['slug']}]: {$throwable->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Permissions processed: {$processed} created/updated, {$skipped} skipped, {$failed} failed.");

        $permissionIds = Permission::query()->pluck('id');

        if ($permissionIds->isEmpty()) {
            $this->warn('No permissions found to attach to roles.');
        }

        $adminSlug = (string) config('authorizo.roles.admin', 'admin');
        $userSlug = (string) config('authorizo.roles.user', 'user');

        try {
            DB::transaction(function () use ($permissionIds, $adminSlug, $userSlug): void {
                $admin = Role::query()->updateOrCreate(
                    ['slug' => $adminSlug],
                    ['name' => Str::headline($adminSlug), 'slug' => $adminSlug],
                );
                $admin->permissions()->syncWithPivotValues($permissionIds, ['allowed' => true]);

                $user = Role::query()->updateOrCreate(
                    ['slug' => $userSlug],
                    ['name' => Str::headline($userSlug), 'slug' => $userSlug],
                );
                $user->permissions()->syncWithPivotValues($permissionIds, ['allowed' => false]);
            });

            $this->info('Default roles created and permissions attached successfully.');
        } catch (Throwable $throwable) {
            $this->error("Failed to create/update roles: {$throwable->getMessage()}");

            return self::FAILURE;
        }

        $this->info('Seeding...');
        $this->call('db:seed', [
            '--class' => 'Authorizo\Database\Seeders\AuthorizoSeeder',
            '--force' => true,
        ]);
        $this->info('Seeding successfully.');
        $this->newLine(1);
        $this->info('Default user (Admin) created');
        $this->info('Name : Admin');
        $this->newLine(1);
        $this->info('Email : admin@example.com');
        $this->newLine(1);
        $this->info('Password : 12345678');
        $this->newLine(1);

        $this->info('Authorizo installation completed successfully.');

        return self::SUCCESS;
    }
}
