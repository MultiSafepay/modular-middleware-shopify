# A package for modular middleware

## Preparation

Please add the following values to your .env

```bash
SHOPIFY_{{GATEWAYID}}_KEY=KEY
SHOPIFY_{{GATEWAYID}}_SECRET=SECRET
```

## Installation

You can install the package via composer:

```bash
composer require multisafepay/modular-middleware-shopify
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="modular-middleware"
php artisan migrate
```
## Finialize

You must add the providers into config/app.php
```bash
\ModularShopify\ModularShopify\ModularShopifyServiceProvider::class,
Laravel\Socialite\SocialiteServiceProvider::class,
\SocialiteProviders\Manager\ServiceProvider::class,
```

Also add the event listener for socialite in Providers/EventServiceProvider.php
```bash
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SocialiteWasCalled::class => [
            'SocialiteProviders\\Shopify\\ShopifyExtendSocialite@handle',
        ],
    ];
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Multisafepay](https://github.com/Multisafepay)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
