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
use ModularShopify\ModularShopify\Middleware\VerifyShopifyRequest;
use ModularShopify\ModularShopify\Middleware\ShopifyWebhookValidator;
use ModularShopify\ModularShopify\Middleware\VerifyMultiSafepayNotification;
use ModularShopify\ModularShopify\Middleware\VerifyShopifySession;
use ModularShopify\ModularShopify\Middleware\IpRestricted;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\SubstituteBindings;

Route::group([
    'prefix' => 'shopify',
    'as' => 'shopify.',
    'middleware' => [SubstituteBindings::class, VerifyCsrfToken::class, 'web']
], function () {

    Route::middleware([VerifyShopifyRequest::class])->group(function () {
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
        Route::get('/shop', [PreferenceController::class, 'get'])->middleware(VerifyShopifySession::class)->name('preference.get');
        Route::post('/shop', [PreferenceController::class, 'save'])->middleware(VerifyShopifySession::class)->name('preference.save');
        Route::post('/shop/activate', [PreferenceController::class, 'activate'])->middleware(VerifyShopifySession::class)->name('preference.activate');
    });

    Route::post('/notification', NotificationController::class)->middleware(VerifyMultiSafepayNotification::class)->name('notification');

    Route::get('/redirect', RedirectController::class)->name('redirect');

    Route::group(['prefix' => 'gdpr', 'middleware' => ShopifyWebhookValidator::class], function () {
        Route::post('/customers/redact', [GdprController::class, 'customersRedact'])->name('gdpr.customers.redact');
        Route::post('/shop/redact', [GdprController::class, 'shopRedact'])->name('gdpr.shop.redact');
        Route::post('/customers/data-request', [GdprController::class, 'customersDataRequest'])->name('gdpr.customers.data_request');
    });

    Route::group(['prefix' => 'admin', 'middleware' => IpRestricted::class], function () {
        Route::get('/shop',  [AdminController::class, 'getShopsView']);
        Route::get('/shop/json',  [AdminController::class, 'getShopsJson']);
    });
});
