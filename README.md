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

## Shopify installation process

Press the install button to install the app.
<img width="635" alt="Install unlisted app IDEAL" src="https://user-images.githubusercontent.com/11698153/210218679-da81da4f-6ee9-4502-9294-095ab62765be.PNG">

Put your MultiSafepay API key and press save. This is only for the first time. After this you only have to press activate if you want to install another paymentmethod.
<img width="793" alt="Save and continue IDEAL" src="https://user-images.githubusercontent.com/11698153/210219535-8a2926fd-6ab8-4c49-bcfe-8673bf0186ce.PNG">

Activate the payment method.
<img width="719" alt="Activate IDEAL" src="https://user-images.githubusercontent.com/11698153/210219561-f28e0eb2-4dbb-4054-89d9-0ae4cc056011.PNG">


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
