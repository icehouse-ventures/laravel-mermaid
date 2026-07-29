<?php

namespace IcehouseVentures\LaravelMermaid\Tests;

use IcehouseVentures\LaravelMermaid\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
