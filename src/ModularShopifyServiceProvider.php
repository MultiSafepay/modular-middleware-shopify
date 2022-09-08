<?php

namespace ModularShopify\ModularShopify;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ModularShopify\ModularShopify\Commands\ModularShopifyCommand;

class ModularShopifyServiceProvider extends PackageServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(base_path('routes/shopify.php'));
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('modular-middleware-shopify')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_modular-middleware-shopify_table')
            ->hasCommand(ModularShopifyCommand::class);
    }
}
