<?php

namespace ModularShopify\ModularShopify\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use ModularMultiSafepay\ModularMultiSafepay\MultiSafepay;
use ModularShopify\ModularShopify\API\ShopifyGraphQL;
use ModularShopify\ModularShopify\Models\Shopify;
use ModularShopify\ModularShopify\Models\ShopifyOrders;

class processKeepOrdersExist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shop;
    protected $order;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = [60];

    public function __construct(ShopifyOrders $order ,Shopify $shop)
    {
        $this->shop = $shop;
        $this->order = $order;
    }

    public function tags(){
        return ["order", "order_id: ". $this->order->order_id];
    }

    public function handle(MultiSafepay $multiSafepay): void
    {
        $transaction = $multiSafepay->getTransaction($this->shop->multisafepay_api_key,$this->order->order_id);

        //If order is complete delete order
        if ($transaction->status === "completed"){
            $this->order->forceDelete();
            Log::info("KeepOrderExist Deleting order: " . $this->order->order_id,['reason' => 'completed']);
            return;
        }
        //If order passed the expire date than delete
        if (now() > $this->order->expire_date){
            $this->order->forceDelete();
            Log::info("KeepOrderExist Deleting order: " . $this->order->order_id,['reason' => 'expire']);
            return;
        }
        //Let shopify know the order is still alive
        ShopifyGraphQL::pendingPayment($this->shop,$this->order->order_id,$this->order->gateway);
        Log::info("KeepOrderExist still pending order: " . $this->order->order_id,['reason' => 'Pending']);
    }

    public function fail($exception = null)
    {
        Log::error("KeepOrderExist failed process order: " . $this->order->order_id,[$exception]);
    }
}
