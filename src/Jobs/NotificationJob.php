<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\Jobs;

use ModularShopify\ModularShopify\API\ShopifyGraphQL;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 4;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = [30, 300, 1800, 3600];

    public function __construct(private $gid, private $shop, private $order)
    {
    }

    public function tags(){
        return ["order", "order_id: ". $this->order['order_id']];
    }

    public function handle(): void
    {
        Log::info('Received notification for order id: ' . $this->order['order_id'] . ' for domain: ' . $this->shop, [
            'event' => 'notification_request',
            'order_id' => $this->order['order_id'],
            'transaction_id' => $this->order['transaction_id'],
            'status' => $this->order['status'],
            'financial_status' => $this->order['financial_status'],
            'gid' => $this->gid,
            'domain' => $this->shop['domain']
        ]);

        if ($this->order['status'] === 'initialized') {
            $response = ShopifyGraphQL::pendingPayment($this->shop, $this->order['order_id'], $this->order['payment_details']['type']);

            if ($response->hasSucceeded()) {
                Log::info('Pending payment via notification for order id: ' . $this->order['order_id'], [
                    'event' => 'notification_payment_pending',
                    'response' => $response->getData(),
                    'message' => $response->getMessage()
                ]);
            } else {
                Log::error('Could not pend payment via notification for order id:' . $this->order['order_id'], [
                    'event' => 'notification_payment_pending_failed',
                ]);
            }
        }

        if (
            $this->order['status'] === 'declined' ||
            $this->order['status'] === 'expired' ||
            $this->order['status'] === 'void'
        ) {
            $reasonCode = $this->order['reason_code'] ?? 'PROCESSING_ERROR';
            $reason = $this->order['reason'] ?? 'Could not process payment';

            $response = ShopifyGraphQL::rejectPayment($this->shop, $this->gid, $reasonCode, $reason, $this->order['payment_details']['type']);

            if ($response->hasSucceeded()) {
                Log::info('Reject payment via notification for order id: ' . $this->order['order_id'], [
                    'event' => 'notification_payment_reject',
                    'response' => $response->getData(),
                    'message' => $response->getMessage()
                ]);
            } else {
                Log::error('Could not reject payment via notification for order id:' . $this->order['order_id'], [
                    'event' => 'notification_payment_reject_failed',
                ]);
            }
        }

        if ($this->order['status'] === 'completed') {
            Log::info('Trying to resolve payment for order id: ' . $this->order['order_id'], ['event' => 'notification_payment_resolve']);

            $response = ShopifyGraphQL::resolvePayment($this->shop, $this->order['order_id'], $this->order['payment_details']['type']);

            if ($response->hasSucceeded()) {
                Log::info('Resolved payment via notification for order id: ' . $this->order['order_id'], [
                    'event' => 'notification_payment_resolved',
                    'response' => $response->getData(),
                    'message' => $response->getMessage()
                ]);
            } else {
                Log::error('Could not resolve payment via notification for order id:' . $this->order['order_id'], [
                    'event' => 'notification_payment_resolve_failed',
                ]);
            }
        }

        Log::info('Finished notification for order id:' . $this->order['order_id'], ['event' => 'notification_response']);
    }
}
