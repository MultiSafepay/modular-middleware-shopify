<?php

// config for ModularShopify/ModularShopify
return [
    'eps' => [
        'secret' => env('SHOPIFY_EPS_SECRET', ''),
        'key' => env('SHOPIFY_EPS_KEY', '')
    ],
    'creditcard' => [
        'secret' => env('SHOPIFY_CREDITCARD_SECRET', ''),
        'key' => env('SHOPIFY_CREDITCARD_KEY', '')
    ]
];
