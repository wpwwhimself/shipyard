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
    private const CONFIGS = [
        "audit",
        "backup",
        "blade-icons",
        "popper", // this one doesn't work by merge, so "install" still must copy it
        "sanctum",
    ];

    public function register()
    {
        // initiates shipyard's defaults for other packages
        foreach (self::CONFIGS as $config) {
            $this->mergeConfigFrom(__DIR__ . "/../files/configs/$config.php", "shipyard.$config");
        }
    }

    public function boot()
    {
        // overrides other packages' configs
        foreach (self::CONFIGS as $config) {
            $new_config = array_replace_recursive(
                config($config) ?? [],
                config("shipyard.$config")
            );
            config([$config => $new_config]);
        }

        $this->loadMigrationsFrom(__DIR__ . "/../files/migrations");
        $this->loadViewsFrom(__DIR__ . "/../files/views", "shipyard");
        Blade::anonymousComponentPath("shipyard::components", "shipyard");

        $this->loadRoutesFrom(__DIR__ . "/../files/routes/shipyard.php");
        $this->loadRoutesFrom(__DIR__ . "/../files/routes/shipyard_api.php");
        $this->loadRoutesFrom(__DIR__ . "/../files/routes/shipyard_schedule.php");

        $this->publishes([
            __DIR__ . "/../files/configs/audit.php" => config_path("audit.php"),
            __DIR__ . "/../files/configs/backup.php" => config_path("backup.php"),
            __DIR__ . "/../files/configs/blade-icons.php" => config_path("blade-icons.php"),
            __DIR__ . "/../files/configs/popper.php" => config_path("popper.php"),
            __DIR__ . "/../files/configs/sanctum.php" => config_path("sanctum.php"),
        ]);

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
