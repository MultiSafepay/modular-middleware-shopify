<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\Jobs;

use ModularMultiSafepay\ModularMultiSafepay\MultiSafepay;
use ModularShopify\ModularShopify\API\Refund;
use ModularShopify\ModularShopify\API\ShopifyGraphQL;
use App\Models\Shopify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use ModularMultiSafepay\ModularMultiSafepay\Refund as MSPRefund;

class CreateRefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Refund $refund)
    {
    }

    public function handle(MultiSafepay $multiSafepay): void
    {
        Log::info('Refund job started for ' . $this->refund->getTransactionId() . ' at store ' . $this->refund->getDomain(),
            ['refund' => $this->refund->getRefundGid(),
                'transaction' => $this->refund->getTransactionId(),
                'domain' => $this->refund->getDomain(),
                'event' => 'refund_job_started',
            ]);
        $shop = Shopify::retrieveByUrl($this->refund->getDomain());

        if (!$shop) {
            Log::error('Could not create refund for' . $this->refund->getTransactionId() . ' domain did not a have store in database', ['domain' => $this->refund->getDomain(), 'event' => 'refund_job_no_shop',]);
        }

        $refund = new MSPRefund\Refund($this->refund->getTransactionId(), $this->refund->getRefundId(), $this->refund->getAmount(), $this->refund->getCurrency(),);
        $hasSucceeded = $multiSafepay->createRefund($shop->multisafepay_api_key, $refund);
        if ($hasSucceeded) {
            ShopifyGraphQL::resolveRefund($shop, $this->refund->getRefundGid(), '');
            Log::info('Resolved refund for transaction ' . $this->refund->getTransactionId(), ['event' => 'refund_job_resolve', 'refund' => $this->refund->getRefundGid(), 'Refund: ' . $this->refund->getTransactionId()]);
        } else {
            ShopifyGraphQL::rejectRefund($shop, $this->refund->getRefundGid(), 'refund failed');
            Log::error('Rejected refund for transaction ' . $this->refund->getTransactionId() . ' could not create refund', ['message' => 'refund failed', 'event' => 'refund_job_rejected', 'refund' => $this->refund->getRefundGid(),]);
        }
    }
}
