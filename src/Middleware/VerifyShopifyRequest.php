<?php

namespace ModularShopify\ModularShopify\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyShopifyRequest
{
    /**
     * Handle an incoming request.
     *
     * @see https://shopify.dev/apps/auth/oauth#verification
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isValidHostname($request->shop)) {
            Log::error('Could not verify request: invalid shop hostname', ['event' => 'invalid_shop_hostname']);
            return response('Could not verify request: invalid shop hostname', 403);
        }
        try {
            $isValidRequest = self::isValidShopifyRequest($request);
            if (!$isValidRequest) {
                Log::error('Could not verify request: HMAC verification failed', [
                    'event' => 'hmac_failed',
                    'domain' => $request->shop,
                ]);
                return response('Could not verify request: HMAC verification failed', 403);
            }
        } catch (\Exception $exception) {
            Log::error('Could not verify request', [
                'event' => 'invalid_shopify_request',
                'error' => $exception->getMessage(),
            ]);
            return response('Could not verify request: ' . $exception->getMessage(), 403);
        }

        return $next($request);
    }

    /**
     * @see https://shopify.dev/apps/auth/oauth#security-checks
     */
    private function isValidHostname($shop): bool
    {
        return preg_match('/\A[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com\z/', $shop);
    }

    public static function isValidShopifyRequest(Request $request): bool
    {
        $data = $request->all();

        if (isset($data['hmac'])) {
            $hmac = $data['hmac'];
            unset($data['hmac']);
        } else {
            throw new \Exception("HMAC value not found in url parameters.");
        }

        //Create data string for the remaining url parameters
        $dataString = self::buildQueryString($data);
        $clientSecret = config('shopify.' . $request->get('gateway') . '.key');

        Log::info('GATEWAY',[$clientSecret,config($clientSecret)]);

        $realHmac = hash_hmac('sha256', $dataString, config($clientSecret));

        //hash the values before comparing (to prevent time attack)
        return md5($realHmac) === md5($hmac);
    }

    public static function buildQueryString($data): string
    {
        $paramStrings = [];
        foreach ($data as $key => $value) {
            $paramStrings[] = "$key=$value";
        }
        return implode('&', $paramStrings);
    }
}
