<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShopifyWebhookValidator
{
    /**
     * Handle an incoming Shopify webhook.
     *
     * @see https://shopify.dev/apps/webhooks#6-verify-a-webhook
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');
        $payload = $request->getContent();

        if (!$this->isValidHmac($hmacHeader, $payload)) {
            return response('Could not verify webhook: HMAC verification failed', 403);
        }

        return $next($request);
    }

    private function isValidHmac($hmacHeader, $payload): bool
    {
        if ($hmacHeader === null) {
            return false;
        }

        $realHmac = base64_encode(hash_hmac('sha256', $payload, config('services.shopify.client_secret'), true));
        if (!hash_equals($hmacHeader, $realHmac)) {
            return false;
        }

        return true;

    }
}
