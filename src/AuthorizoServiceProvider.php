<?php

declare(strict_types=1);

namespace Authorizo\Authorizo;

use Authorizo\Authorizo\Console\Commands\AuthorizoCommand;
use Authorizo\Authorizo\Middleware\AuthorizoMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AuthorizoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/authorizo.php', 'authorizo');

        $this->app->singleton(Authorizo::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void
    {
        $router->aliasMiddleware('authorizo', AuthorizoMiddleware::class);

        if (config('authorizo.routes.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/authorizo.php');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'authorizo');

        Blade::anonymousComponentPath(
            __DIR__.'/../resources/views/components',
            'authorizo',
        );

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'authorizo');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/authorizo.php' => config_path('authorizo.php'),
        ], ['authorizo', 'authorizo-config']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/authorizo'),
        ], ['authorizo', 'authorizo-views']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/authorizo'),
        ], ['authorizo', 'authorizo-lang']);

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['authorizo', 'authorizo-migrations']);

        $this->commands([
            AuthorizoCommand::class,
        ]);
    }
}
