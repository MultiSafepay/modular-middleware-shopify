<?php declare(strict_types=1);

namespace ModularShopify\ModularShopify\Controllers;

use ModularMultiSafepay\ModularMultiSafepay\MultiSafepay;
use ModularMultiSafepay\ModularMultiSafepay\MultisafepayClient;
use ModularShopify\ModularShopify\API\ShopifyGraphQL;
use App\Http\Controllers\Controller;
use ModularShopify\ModularShopify\Requests\RedirectRequest;
use ModularShopify\ModularShopify\Models\Shopify;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    public function __invoke(RedirectRequest $redirectRequest): RedirectResponse
    {
        $domain = urldecode($redirectRequest->validated()['domain']);
        $shop = Shopify::retrieveByUrl($domain);
        $multiSafepay = new MultiSafepay(new MultisafepayClient($shop->multisafepay_environment));

        if (!$shop) {
            Log::error('Shop not found', ['domain' => $domain]);
            throw new \Exception('could not find store ' . $domain);
        }

        $response = ShopifyGraphQL::pendingPayment(
            $shop,
            $redirectRequest->validated()['id'],
            $multiSafepay->getTransaction($shop->multisafepay_api_key, $redirectRequest->validated()['id'])->paymentDetails->type
        );

        $redirectUrl = $response->getData()['data']['paymentSessionPending']['paymentSession']['nextAction']['context']['redirectUrl'];

        Log::info('Trying to redirect user to redirect_url', [
            'event' => 'redirect_customer',
            'customer' => $redirectRequest->ip(),
            'redirect_url' => $redirectUrl,
        ]);
        return response()->redirectTo($redirectUrl);
    }
}
