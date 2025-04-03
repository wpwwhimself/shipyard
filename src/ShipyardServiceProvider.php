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
        ]);

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
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'shipyard');
    }

    public function provides()
    {
        return [
            \Wpwwhimself\Shipyard\Console\InstallCommand::class,
        ];
    }
}
