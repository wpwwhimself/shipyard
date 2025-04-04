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
    }

    public function provides()
    {
        return [
            \Wpwwhimself\Shipyard\Console\InstallCommand::class,
        ];
    }
}
