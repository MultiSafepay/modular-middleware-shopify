<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API;


use ModularShopify\ModularShopify\API\Calls\PaymentsAppConfigure;
use ModularShopify\ModularShopify\API\Calls\PaymentSessionReject;
use ModularShopify\ModularShopify\API\Calls\PaymentSessionResolve;
use ModularShopify\ModularShopify\API\Calls\PaymentSessionPending;
use ModularShopify\ModularShopify\API\Calls\RefundSessionReject;
use ModularShopify\ModularShopify\API\Calls\RefundSessionResolve;
use ModularShopify\ModularShopify\API\Calls\WebhookSubscriptionCreate;
use App\Models\Shopify;

final class ShopifyGraphQL
{
    public static function resolvePayment(Shopify $shop, string $id, string $gateway)
    {
        $id = 'gid://shopify/PaymentSession/' . $id;

        return GraphQLCall::execute(
            $shop,
            PaymentSessionResolve::QUERY,
            PaymentSessionResolve::formatVariables($id),
            $gateway
        );
    }

    public static function rejectPayment(Shopify $shop, string $id, string $reasonCode, $merchantMessage, string $gateway): GraphQLResponse
    {
        return GraphQLCall::execute(
            $shop,
            PaymentSessionReject::QUERY,
            PaymentSessionReject::formatVariables($id, $reasonCode, $merchantMessage),
            $gateway
        );
    }

    public static function pendingPayment(Shopify $shop, string $id, string $gateway): GraphQLResponse
    {
        $id = 'gid://shopify/PaymentSession/' . $id;

        return GraphQLCall::execute(
            $shop,
            PaymentSessionPending::QUERY,
            PaymentSessionPending::formatVariables($id, 'payafter'),
            $gateway
        );
    }

    public static function configureApp(Shopify $shop, bool $ready, string $gateway): GraphQLResponse
    {
        return GraphQLCall::execute(
            $shop,
            PaymentsAppConfigure::QUERY,
            PaymentsAppConfigure::formatVariables($ready),
            $gateway
        );
    }

    public static function rejectRefund(
        Shopify $shop,
        string $id,
        string $merchantMessage,
        string $gateway,
        string $reasonCode = 'PROCESSING_ERROR',
    ): GraphQLResponse
    {
        return GraphQLCall::execute(
            $shop,
            RefundSessionReject::QUERY,
            RefundSessionReject::formatVariables($id, $reasonCode, $merchantMessage),
            $gateway
        );
    }

    public static function resolveRefund(Shopify $shop, string $id, string $gateway): GraphQLResponse
    {
        return GraphQLCall::execute(
            $shop,
            RefundSessionResolve::QUERY,
            RefundSessionResolve::formatVariables($id),
            $gateway
        );
    }
}
