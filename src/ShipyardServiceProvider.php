<?php

namespace Wpwwhimself\Shipyard;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ShipyardServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . "/../files/views", "shipyard");

        Blade::anonymousComponentPath("shipyard::components", "shipyard");

        $this->commands([
            \Wpwwhimself\Shipyard\Console\InstallCommand::class,
            \Wpwwhimself\Shipyard\Console\UninstallCommand::class,
            \Wpwwhimself\Shipyard\Console\CacheThemeCommand::class,
            \Wpwwhimself\Shipyard\Console\WhatNowCommand::class,
            \Wpwwhimself\Shipyard\Console\PrybarCommand::class,
        ]);
    }

    public function provides()
    {
        return [
            \Wpwwhimself\Shipyard\Console\InstallCommand::class,
            \Wpwwhimself\Shipyard\Console\UninstallCommand::class,
            \Wpwwhimself\Shipyard\Console\CacheThemeCommand::class,
            \Wpwwhimself\Shipyard\Console\WhatNowCommand::class,
            \Wpwwhimself\Shipyard\Console\PrybarCommand::class,
        ];
    }
}
