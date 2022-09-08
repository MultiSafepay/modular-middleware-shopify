<?php

namespace ModularShopify\ModularShopify\Controllers;

use ModularShopify\ModularShopify\API\Refund;
use App\Http\Controllers\Controller;
use App\Jobs\Shopify\CreateRefundJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    /**
     * @see https://shopify.dev/apps/payments/processing-a-refund#initiate-the-refund-flow
     */
    public function __invoke(Request $request)
    {
        Log::info('Received refund request for ' . $request->header('shopify-shop-domain') . ' from ' . $request->get('payment_id'), ['event' => 'refund_dispatch']);

        $transactionId = $request->get('payment_id');
        $refundId = $request->get('id');
        $refundGid = $request->get('gid');
        $amount = (int)round($request->get('amount') * 100, 0);
        $currency = $request->get('currency');
        $domain = $request->header('shopify-shop-domain');

        $refund = new Refund($domain, $transactionId, $refundId, $refundGid, $amount, $currency);
        Log::info('Dispatching refund for ' . $request->get('payment_id'), ['event' => 'refund_dispatch', 'refund' => $refund]);

        // Cannot resolve before shopify has received a 201, so wait a few seconds.
        CreateRefundJob::dispatch($refund)->onQueue('refunds')->delay(now()->addSeconds(5));

        return response('', 201);
    }
}
