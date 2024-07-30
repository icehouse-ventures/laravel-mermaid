<?php

namespace IcehouseVentures\LaravelMermaid\Facades;

use Illuminate\Support\Facades\Facade;

class Mermaid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mermaid';
    }
}