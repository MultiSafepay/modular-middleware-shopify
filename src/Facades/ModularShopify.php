<?php

namespace ModularShopify\ModularShopify\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ModularShopify\ModularShopify\ModularShopify
 */
class ModularShopify extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ModularShopify\ModularShopify\ModularShopify::class;
    }
}
