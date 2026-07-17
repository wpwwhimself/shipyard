<?php

namespace Wpwwhimself\Shipyard;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Wpwwhimself\Shipyard\Console\CacheThemeCommand;
use Wpwwhimself\Shipyard\Console\InstallCommand;
use Wpwwhimself\Shipyard\Console\PrybarCommand;
use Wpwwhimself\Shipyard\Console\UninstallCommand;
use Wpwwhimself\Shipyard\Console\WhatNowCommand;

class ShipyardServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../files/migrations");
        $this->loadViewsFrom(__DIR__ . "/../files/views", "shipyard");

        // routes
        $this->loadRoutesFrom(__DIR__ . "/../files/routes/shipyard.php");
        $this->loadRoutesFrom(__DIR__ . "/../files/routes/shipyard_api.php");
        $this->loadRoutesFrom(__DIR__ . "/../files/routes/shipyard_schedule.php");

        Blade::anonymousComponentPath("shipyard::components", "shipyard");

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                UninstallCommand::class,
                CacheThemeCommand::class,
                WhatNowCommand::class,
                PrybarCommand::class,
            ]);
        }
    }

    public function provides()
    {
        return [
            InstallCommand::class,
            UninstallCommand::class,
            CacheThemeCommand::class,
            WhatNowCommand::class,
            PrybarCommand::class,
        ];
    }
}
