<?php declare(strict_types=1);

namespace ModularShopify\ModularShopify\Controllers;

use ModularShopify\ModularShopify\Models\Shopify;

class AdminController
{
    public function getShopsView()
    {
        return response()->view('shopify.admin.shop', [
            'shops' => Shopify::all()
        ]);
    }

    public function getShopsJson()
    {
        return Shopify::all();
    }
}
