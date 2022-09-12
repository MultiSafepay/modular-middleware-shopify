<?php

namespace ModularShopify\ModularShopify\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyShopifySession
{
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();
        $key = config('shopify.' . $request->get('gateway') . '.secret');
        Log::debug('VerifyShopifySession', [
            'jwt' => $jwt,
            'key' => $key,
            'gateway' => $request->get('gateway'),
            'config' => config()
        ]);

        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));


        $decoded_array = (array)$decoded;
        if (!str_contains($decoded_array['dest'], $request->get('shop'))) {
            throw new \Exception();
        }


        Log::debug('VerifyShopifySession array', $decoded_array);

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
    }
}
