<?php

namespace Shipyard;

use Illuminate\Support\ServiceProvider;

class ShipyardServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../config/shipyard.php', 'shipyard');
    }

    public function boot()
    {
        // Load routes and views
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'shipyard');

        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/shipyard.php' => config_path('shipyard.php'),
        ]);
    }
}
