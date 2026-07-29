<?php

namespace IcehouseVentures\LaravelMermaid;

use IcehouseVentures\LaravelMermaid\Support\Builder;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'mermaid');

        $this->publishes([
            __DIR__.'/../config/mermaid.php' => config_path('mermaid.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mermaid.php', 'mermaid');

        // This interface allows usage via `app('mermaid')->generateDiagramFromArray();`
        $this->app->bind('mermaid', function (): Builder {
            return new Builder;
        });

        // This interface allows usage via `Mermaid::build()->generateDiagramFromArray();`
        $this->app->singleton('mermaid-facade', function (): Builder {
            return new Builder;
        });
    }
}
