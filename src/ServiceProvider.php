<?php

namespace IcehouseVentures\LaravelMermaid;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use IcehouseVentures\LaravelMermaid\Support\Builder;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'mermaid');

        
        $this->publishes([
            __DIR__ . '/../config/mermaid.php' => config_path('mermaid.php'),
        ], 'config');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mermaid.php', 'mermaid');

        $this->app->bind('mermaid', function () {
            return new Builder();
        });
    }
}