<?php

namespace ModularShopify\ModularShopify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopifyOrders extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'shopify_id',
        'order_id',
        'gateway',
        'expire_date',
    ];

    public function CreateOrder(int $shopify_id, string $order_id, string $gateway, $date): ShopifyOrders
    {
        return self::create([
            'shopify_id' => $shopify_id,
            'order_id' => $order_id,
            'gateway' => $gateway,
            'expire_date' => $date,
        ]);
    }

    public function shopify()
    {
        return $this->belongsTo(Shopify::class);
    }
}
