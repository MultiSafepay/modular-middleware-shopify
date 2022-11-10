<?php

namespace ModularShopify\ModularShopify\Controllers;

use ModularMultiSafepay\ModularMultiSafepay\MultiSafepay;
use ModularMultiSafepay\ModularMultiSafepay\MultisafepayClient;
use ModularMultiSafepay\ModularMultiSafepay\Order\Data;
use ModularMultiSafepay\ModularMultiSafepay\Order\Order;
use ModularShopify\ModularShopify\API\Request\Payment;
use ModularShopify\ModularShopify\API\ShopifyGraphQL;
use App\Http\Controllers\Controller;
use ModularShopify\ModularShopify\Models\Shopify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * @see https://shopify.dev/apps/payments/processing-a-payment#initiate-the-payment-flow
     */
    public function __invoke(Request $request)
    {
        Log::info('Received payment request from domain: ' . ($request->header('Shopify-Shop-Domain') ?? 'Unknown'), ['event' => 'payment_request']);
        $domain = $request->header('Shopify-Shop-Domain');
        $shop = Shopify::retrieveByUrl($domain);

        $multiSafepay = new MultiSafepay(new MultisafepayClient($shop->multisafepay_environment));
        $payment = new Payment($request->all(), $domain);

        if (!$shop) {
            Log::error('Could not find a store with domain ' . ($domain ?? 'Unknown') . ', redirecting customer to cancel url', ['domain' => $domain, 'event' => 'payment_request']);
            // Shop could not be found, most likely needs some sort of error detection
            return response()->json([
                'redirect_url' => $payment->getCancelUrl()
            ], 422); // 422, request was ok, entity unprocessable
        }

        // We don't support Authorize payment flow
        if ($payment->isAuthorization()) {
            Log::error('Received unsupported authorize payment request for ' . ($domain ?? 'Unknown'), ['event' => 'payment_request']);
            ShopifyGraphQL::rejectPayment($shop, $request->get('gid'), 'PROCESSING_ERROR', 'We do not support Authorize payment flow', $request->query('gateway'));
            return response('', Response::HTTP_NOT_IMPLEMENTED);
        }

        Log::info('Format transaction request from store ' . $domain . ' for payment ' . $payment->getOrderId(), ['event' => 'payment_request']);

        $order = new Order(
            $payment->getOrderId(),
            $payment->getAmountInCents(),
            $payment->getCurrency(),
            $request->query('gateway'),
            'redirect',
            $payment->getDescription(),
            $payment->getPaymentOptions(),
            $payment->getCustomerInfo(),
            $payment->getShippingInfo(),
            null,
            null,
            new Data($domain, $payment->getGid()),
            3 // 3 days active since shopify only allows us for 3 days to be active
        );

        $paymentUrl = $multiSafepay->createTransaction($shop->multisafepay_api_key, $order);
        // $paymentUrl = $multiSafepayService->createTransaction($shop, $transaction);
        Log::info('Finished payment request from store ' . $domain . ' redirecting user to ' . $paymentUrl, ['event' => 'payment_request']);

        return response([
            'redirect_url' => $paymentUrl
        ], 201);
    }
}
