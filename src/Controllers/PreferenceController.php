<?php declare(strict_types=1);

namespace ModularShopify\ModularShopify\Controllers;

use App\Data\Multisafepay\MultiSafepay;
use ModularShopify\ModularShopify\API\ShopifyGraphQL;
use App\Http\Controllers\Controller;

use App\Models\Shopify;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PreferenceController extends Controller
{
    public function view(Request $request): Response
    {
        Log::info('Received preference view request', ['event' => 'shopify.preference_view']);
        return response()->view('shopify.preference');
    }

    public function get(Request $request, MultiSafepay $multiSafepay): JsonResponse
    {
        $subdomain = $request->get('shop');
        $shop = Shopify::retrieveByUrl($subdomain);

        $gateway = true;

        if ($shop->multisafepay_api_key) {
            $gateway = $multiSafepay->getGateway($shop->multisafepay_api_key, $request->get('gateway'))["success"];
        }

        $isActivated = false;

        if ($shop->accessTokens->where('gateway', $request->get('gateway'))->first()){
            $isActivated = (bool)$shop->accessTokens->where('gateway', $request->get('gateway'))->first()->activated;
        }

        return \response()->json([
            'apiKey' => $shop->multisafepay_api_key,
            'environment' => $shop->multisafepay_environment,
            'activated' => $isActivated,
            'enabledGateway' => $gateway
        ]);
    }

    public function activate(Request $request, MultiSafepay $multiSafepay): JsonResponse
    {
        Log::channel('sentry')->info('received preference save request', ['event' => 'preference_save']);
        $subdomain = $request->get('shop');
        $shop = Shopify::retrieveByUrl($subdomain);

        $gateway = $multiSafepay->getGateway($shop->multisafepay_api_key, $request->get('gateway'))["success"];

        if (!$gateway) {
            return \response()->json([
                'type' => 'error',
                'error' => [
                    'Payment method not enabled'
                ]
            ]);
        }

        if ($this->enablePaymentApp($subdomain, $request->get('gateway'))) {
            // App installation successful, send user back to shopify
            $redirectUrl = $this->getPaymentAppUrl($subdomain, $request->get('clientKey'));

            $shopify = shopify::where('shopify_domain', $subdomain)->first();

            $token = $shopify->accessTokens->where('gateway', $request->get('gateway'))->first();
            $token->activated = true;
            $token->save();

            Log::info('Enable app, redirect used',
                ['event' => 'preference_save_enabled_app', 'domain' => $subdomain, 'url' => $redirectUrl]);
            return response()->json([
                'type' => 'redirect',
                'url' => $redirectUrl
            ]);
        }

        return \response()->json([
            'type' => 'error',
            'error' => [
                'something went wrong activating your gateway.'
            ]
        ]);
    }

    public function save(Request $request, MultiSafepay $multiSafepay)
    {
        Log::channel('sentry')->info('received preference save request', ['event' => 'preference_save']);
        $subdomain = $request->get('shop');

        $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $apiKey = trim($content['apiKey']) ?? '';
        $environment = $content['environment'] === 'live' ? 'live' : 'test';
        $errors = [];

        Log::info('received preference view request - obtained shop',
            ['event' => 'preference_save_shop', 'domain' => $subdomain]);

        if ($multiSafepay->verifyApiKey($apiKey)) {

            Log::info('updateMultiSafepayEnvironment',
                ['event' => 'preference_save_update_env', 'domain' => $subdomain]);

            $gateway = $multiSafepay->getGateway($content['apiKey'], $request->get('gateway'))["success"];

            if (!$gateway) {
                return response()->json([
                    'type' => 'gatewayError'
                ]);
            }

            $shopify = shopify::where('shopify_domain', $subdomain)->first();

            $shopify->update([
                'multisafepay_api_key' => $apiKey,
                'multisafepay_environment' => $environment
            ]);

            $shopify->accessTokens->where('gateway', $request->get('gateway'))->first()->update([
                'activated' => true
            ]);

            if ($this->enablePaymentApp($subdomain, $request->get('gateway'))) {
                // App installation successful, send user back to shopify
                $redirectUrl = $this->getPaymentAppUrl($subdomain, $request->get('clientKey'));
                Log::info('Enable app, redirect used',
                    ['event' => 'preference_save_enabled_app', 'domain' => $subdomain, 'url' => $redirectUrl]);
                return response()->json([
                    'type' => 'redirect',
                    'url' => $redirectUrl
                ]);
            }
            Log::error('received preference save request - could not enable app',
                ['event' => 'preference_save_no_enable', 'domain' => $subdomain]);

            $errors[] = 'Unable to enable payments app';
        } else {
            Log::error('received preference save request - could not verify apikey',
                ['event' => 'preference_save_no_verify', 'domain' => $subdomain]);
            $errors[] = 'Could not verify API key. Are you sure the correct environment is selected?';
        }

        if (count($errors) > 0) {
            return \response()->json([
                'type' => 'error',
                'error' => $errors
            ]);
        }

        return \response();
    }

    private function enablePaymentApp(string $subdomain, string $gateway): bool
    {
        $shop = Shopify::retrieveByUrl($subdomain);

        if ($shop) {
            $response = ShopifyGraphQL::configureApp($shop, true, $gateway);

            if ($response->hasSucceeded()) {
                Log::info('received preference save request - enabled app',
                    ['event' => 'preference_save_no_enable_message', 'domain' => $subdomain, 'response' => $response]);
                return true;
            }

            Log::error('received preference save request - could not enable app',
                [
                    'event' => 'preference_save_no_enable_message',
                    'domain' => $subdomain,
                    'response' => $response->getMessage()
                ]);
        }

        return false;
    }

    public function getPaymentAppUrl(string $subdomain, string $clientKey): string
    {
        return 'https://' . $subdomain . '/services/payments_partners/gateways/' . $clientKey . '/settings';
    }
}