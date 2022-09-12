<?php

namespace ModularShopify\ModularShopify;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use ModularShopify\ModularShopify\Models\Shopify;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ModularShopify\ModularShopify\Commands\ModularShopifyCommand;

use function Sodium\add;

class ModularShopifyServiceProvider extends PackageServiceProvider
{

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/shopify.php');

        Config::set(
            'horizon.defaults.supervisor-shopify',
            [
                'connection' => 'redis',
                'queue' => ['default','shopify-high','shopify-low'],
                'balance' => 'auto',
                'maxProcesses' => 2,
                'maxTime' => 0,
                'maxJobs' => 0,
                'memory' => 128,
                'tries' => 0,
                'timeout' => 60,
                'nice' => 0,
            ]
        );
    }

    public function configurePackage(Package $package): void
    {

        $this->publishes([
            //Config
            __DIR__.'/../config/shopify.php' => config_path('shopify.php'),
            //Migrations
            __DIR__.'/../database/migrations/create_modular_middleware_shopify_table.php.stub' => database_path('migrations/2022_01_31_101358_create_modular_middleware_shopify_table.php'),
            //Blades
            __DIR__.'/../resources/views/preference.blade.php' => resource_path('views/shopify/preference.blade.php'),
            __DIR__.'/../resources/views/admin/shop.blade.php' => resource_path('views/shopify/admin/shop.blade.php'),
            __DIR__.'/../resources/views/emails/gdpr/customers-data-requested.blade.php' => resource_path('views/shopify/emails/gdpr/customers-data-requested.blade.php'),
            __DIR__.'/../resources/views/emails/gdpr/customers-redacted.blade.php' => resource_path('views/shopify/emails/gdpr/customers-redacted.blade.php'),
            __DIR__.'/../resources/views/emails/gdpr/shop-redacted.blade.php' => resource_path('views/shopify/emails/gdpr/shop-redacted.blade.php'),
        ], 'modular-middleware');
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('modular-middleware-shopify')
            ->hasViews()
            ->hasMigration('create_modular-middleware-shopify_table')
            ->hasCommand(ModularShopifyCommand::class);
    }
}
