<?php

namespace Wpwwhimself\Shipyard;

use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Shipyard\Controllers\AuthController;
use Shipyard\Controllers\DocumentationController;
use Shipyard\Controllers\UserController;

class ShipyardServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../config/shipyard.php', 'shipyard');

        // Controllers
        $this->app->make(AuthController::class);
        $this->app->make(UserController::class);
        $this->app->make(DocumentationController::class);
    }

    public function boot()
    {
        // Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware("role", EnsureUserHasRole::class);

        // Commands
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command("backup:clean")->cron("0 0 * * *");
            $schedule->command("backup:run")->cron("15 0 * * *");
        });

        // Load routes and views
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shipyard');

        // Publish configuration
        $this->publishes([
            // config
            __DIR__.'/../config/shipyard.php' => config_path('shipyard.php'),

            // views
            __DIR__.'/../resources/views/test.blade.php' => resource_path('resources/views/test.blade.php'),
        ]);
    }
}
