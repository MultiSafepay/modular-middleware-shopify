<?php

namespace ModularShopify\ModularShopify\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shopify extends Model implements Authenticatable
{
    use SoftDeletes, HasFactory, \Illuminate\Auth\Authenticatable;

    protected $fillable = [
        'shopify_domain',
        'shopify_access_token',
        'shopify_email',
        'shopify_id',
        'multisafepay_api_key',
        'multisafepay_environment'
    ];

    public static function add(string $url, string $token, string $email, string $id): Shopify
    {
        return self::create([
            'shopify_domain' => $url,
            'shopify_email' => $email,
            'shopify_id' => $id,
        ]);
    }

    public static function retrieveByUrl(string $url): ?Shopify
    {
        return self::where('shopify_domain', $url)->first();
    }

    public static function deleteByUrl(string $url): void
    {
        self::where('shopify_domain', $url)->first()->delete();
    }

    public function accessTokens()
    {
        return $this->hasMany(ShopifyAccessTokens::class);
    }
}
