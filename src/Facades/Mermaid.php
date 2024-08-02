<?php

namespace IcehouseVentures\LaravelMermaid\Facades;

use Illuminate\Support\Facades\Facade;

class Mermaid extends Facade
{
    /**
    * @method static \IcehouseVentures\LaravelMermaid\Support\Builder build()
    */
    protected static function getFacadeAccessor()
    {
        return 'mermaid';
    }
}