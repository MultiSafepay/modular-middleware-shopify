<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\Middleware;

use ModularShopify\ModularShopify\Models\Shopify;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyMultiSafepayNotification
{

    /**
     * Handle an incoming notification from MultiSafepay
     *
     * @see https://docs.multisafepay.com/developer/api/notification-url/#validating-post-notifications
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Auth')) {
            Log::error('Missing Auth header', ['error' => 'missing_auth_header']);
            return response('Missing Auth header', 403);
        }

        if (!$request->has('var1') || !$request->has('var2')) {
            Log::error('Missing var1 or var2', ['error' => 'missing_var1_var2']);
            return response('Unable to find shop domain or order id', 404);
        }

        $shopifyDomain = $request->input('var1');
        $authHeader = $request->header('Auth');

        $shop = Shopify::retrieveByUrl($shopifyDomain);
        if (!$shop) {
            Log::error('Shop not found', ['error' => 'shop_not_found', 'domain' => $shopifyDomain]);
            return response('Unable to find shop', 404);
        }

        $isValidNotification = self::verifyNotification($request->getContent(), $authHeader, $shop->multisafepay_api_key);
        if (!$isValidNotification) {
            Log::error('Invalid notification');
            return response('Invalid notification', 404);
        }

        app()->instance(Shopify::class, $shop);

        return $next($request);
    }

    public static function verifyNotification(string $request, string $auth, string $apiKey, int $validationTimeInSeconds = 600): bool
    {
        $authHeaderDecoded = base64_decode($auth);
        [$timestamp, $sha512hexPayload] = explode(':', $authHeaderDecoded);

        if ($validationTimeInSeconds > 0 && $timestamp + $validationTimeInSeconds < time()) {
            return false;
        }

        $payload = $timestamp . ':' . $request;
        $hash = hash_hmac('sha512', $payload, $apiKey);

        return $hash === $sha512hexPayload;
    }
}
