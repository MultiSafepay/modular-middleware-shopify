<?php declare(strict_types=1);

namespace ModularShopify\ModularShopify\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ModularShopify\ModularShopify\Models\ShopifyOrders;

class KeepOrdersExist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

    public function __construct()
    {
        $this->onQueue('KeepOrdersExist');
    }

    public function handle(): void
    {
        $orders = ShopifyOrders::with('shopify')->get();
        foreach ($orders as $order){
            $shop = $order->shopify;
            processKeepOrdersExist::dispatch($order,$shop)->onQueue('KeepOrdersExist');
        }
    }
}
