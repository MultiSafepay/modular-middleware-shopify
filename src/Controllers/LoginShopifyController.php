<?php declare(strict_types=1);

namespace ModularShopify\ModularShopify\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shopify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\Config;


final class LoginShopifyController extends Controller
{
    public function redirectToProvider(Request $request)
    {
        Log::info('OAuth received request', ['event' => 'redirect_provider']);
        $clientKey = 'SHOPIFY_' . strtoupper($request->query('gateway')) . '_KEY';
        $clientSecret = 'SHOPIFY_' . strtoupper($request->query('gateway')) . '_SECRET';
        $config = new Config(env($clientKey), env($clientSecret), route('shopify.callback') . '?gateway=' . $request->query('gateway'));

        return Socialite::driver('shopify')
            ->setConfig($config)
            ->with([
                'redirect_uri' => route('shopify.callback') . '?gateway=' . $request->query('gateway')
            ])
            ->scopes([
                'write_payment_gateways',
                'write_payment_sessions,'
            ])
            ->redirect();
    }

    /**
     * Handle Shopify callback
     *
     * @see https://shopify.dev/apps/auth/oauth#4-confirm-installation
     */
    public function handleProviderCallback(Request $request)
    {
        Log::info('Handle Callback', ['event' => 'handle_callback']);

        $clientKey = 'SHOPIFY_' . strtoupper($request->query('gateway')) . '_KEY';
        $clientSecret = 'SHOPIFY_' . strtoupper($request->query('gateway')) . '_SECRET';

        if (!$request->getSession()->has('state')) {
            Log::info('Handle Callback, could not find state', ['event' => 'handle_callback_no_state']);
            $shop = Shopify::retrieveByUrl($request->get('shop'));
            Log::info('Shop: ', [$shop]);
        } else {
            Log::info('SHOOPIKEYS', [env($clientKey), env($clientSecret)]);

            $config = new Config(env($clientKey), env($clientSecret), route('shopify.callback') . '?gateway=' . $request->query('gateway'));

            $shopifyUser = Socialite::driver('shopify')
                ->setConfig($config)
                ->user();
            //Log::info('Handle Callback, retrieved user', ['event' => 'handle_callback_user', 'user' => json_encode($shopifyUser)]);

            $shop = Shopify::updateOrCreate(
                [
                    'shopify_domain' => $shopifyUser->nickname
                ],
                [
                    'shopify_domain' => $shopifyUser->nickname,
                    'shopify_email' => $shopifyUser->email,
                    'shopify_id' => (int)$shopifyUser->id
                ]);

            $token = $shop->accessTokens()->updateOrCreate(
                [
                    'shopify_id' => (int)$shop->id,
                    'gateway' => $request->get('gateway')
                ],
                [
                    'shopify_access_token' => $shopifyUser->token,
                    'gateway' => $request->get('gateway')
                ]
            );

            //Log::info('Handle Callback, createOrUpdate user', ['event' => 'handle_callback_user', 'user' => $shopifyUser->nickname]);
        }

        //Log::info('Handle Callback, Auth::login user, redirect to preference', ['event' => 'handle_callback_auth', 'user' => $shop->shopify_domain]);

        return redirect()->route('shopify.preference', [
            'host' => $request->get('host'),
            'shop' => $request->get('shop'),
            'gateway' => $request->get('gateway'),
            'apiKey' => env($clientKey)
        ]);
    }
}
