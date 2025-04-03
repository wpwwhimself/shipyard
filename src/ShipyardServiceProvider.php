<?php

namespace Wpwwhimself\Shipyard;

use Illuminate\Support\ServiceProvider;

class ShipyardServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            \Wpwwhimself\Shipyard\Console\InstallCommand::class,
            \Wpwwhimself\Shipyard\Console\UpdateCommand::class,
        ]);

        // // Migrations
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // // Middleware
        // $router = $this->app->make(Router::class);
        // $router->aliasMiddleware("role", EnsureUserHasRole::class);

        // // Commands
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command("backup:clean")->cron("0 0 * * *");
        //     $schedule->command("backup:run")->cron("15 0 * * *");
        // });

        // // Load routes and views
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'shipyard');

        // // Publish configuration
        // $this->publishes([
        //     // config
        //     __DIR__.'/../config/shipyard.php' => config_path('shipyard.php'),

        //     // views
        //     __DIR__.'/../resources/views/test.blade.php' => resource_path('resources/views/test.blade.php'),
        // ]);
    }

    public function provides()
    {
        return [
            \Wpwwhimself\Shipyard\Console\InstallCommand::class,
            \Wpwwhimself\Shipyard\Console\UpdateCommand::class,
        ];
    }
}
