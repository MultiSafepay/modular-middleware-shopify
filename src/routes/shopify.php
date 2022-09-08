<?php

use ModularShopify\ModularShopify\Controllers\AdminController;
use ModularShopify\ModularShopify\Controllers\CaptureController;
use ModularShopify\ModularShopify\Controllers\GdprController;
use ModularShopify\ModularShopify\Controllers\LoginShopifyController;
use ModularShopify\ModularShopify\Controllers\NotificationController;
use ModularShopify\ModularShopify\Controllers\PaymentController;
use ModularShopify\ModularShopify\Controllers\PreferenceController;
use ModularShopify\ModularShopify\Controllers\RedirectController;
use ModularShopify\ModularShopify\Controllers\RefundController;
use ModularShopify\ModularShopify\Controllers\VoidController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\SubstituteBindings;

Route::group([
    'prefix' => 'shopify',
    'as' => 'shopify.',
    'middleware' => [SubstituteBindings::class, 'web']
], function () {

    Route::middleware(['shopify.verified'])->group(function () {
        Route::get('/login', [LoginShopifyController::class, 'redirectToProvider'])->name('login');
        Route::get('/login/callback', [LoginShopifyController::class, 'handleProviderCallback'])->name('callback');
    });

    Route::group(['prefix' => 'process'], function () {
        Route::post('/payment', PaymentController::class)->name('process.payment');
        Route::post('/refund', RefundController::class)->name('process.refund');
        Route::post('/capture', CaptureController::class)->name('process.capture');
        Route::post('/void', VoidController::class)->name('process.void');
    });

    Route::group(['prefix' => 'preference'], function () {
        Route::get('/', [PreferenceController::class, 'view'])->name('preference');
        Route::get('/shop', [PreferenceController::class, 'get'])->middleware('shopify.verify.session')->name('preference.get');
        Route::post('/shop', [PreferenceController::class, 'save'])->middleware('shopify.verify.session')->name('preference.save');
        Route::post('/shop/activate', [PreferenceController::class, 'activate'])->middleware('shopify.verify.session')->name('preference.activate');
    });

    Route::post('/notification', NotificationController::class)->middleware('notification.verified')->name('notification');

    Route::get('/redirect', RedirectController::class)->name('redirect');

    Route::group(['prefix' => 'gdpr', 'middleware' => 'shopify.webhook.validated'], function () {
        Route::post('/customers/redact', [GdprController::class, 'customersRedact'])->name('gdpr.customers.redact');
        Route::post('/shop/redact', [GdprController::class, 'shopRedact'])->name('gdpr.shop.redact');
        Route::post('/customers/data-request', [GdprController::class, 'customersDataRequest'])->name('gdpr.customers.data_request');
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'multisafepay.restricted'], function () {
        Route::get('/shop',  [AdminController::class, 'getShopsView']);
        Route::get('/shop/json',  [AdminController::class, 'getShopsJson']);
    });
});
