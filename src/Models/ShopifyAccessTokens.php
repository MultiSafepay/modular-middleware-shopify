<?php

namespace ModularShopify\ModularShopify\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopifyAccessTokens extends Model implements Authenticatable
{
    use SoftDeletes, HasFactory, \Illuminate\Auth\Authenticatable;

    protected $fillable = [
        'shopifies_id',
        'shopify_access_token',
        'gateway',
        'activated',
    ];

    public function shopify()
    {
        return $this->belongsTo(Shopify::class);
    }
}
