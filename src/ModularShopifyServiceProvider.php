<?php

namespace ModularShopify\ModularShopify;

use Illuminate\Support\Facades\Config;
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
                'queue' => ['default','notifications-high','notifications-low'],
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
