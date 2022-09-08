<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API\Calls;


final class PaymentSessionReject
{
    public const QUERY = <<<Query
            mutation PaymentSessionReject(\$id: ID!, \$reason: PaymentSessionRejectionReasonInput!) {
                paymentSessionReject(id: \$id, reason: \$reason) {
                    paymentSession {
                        id
                    }
                    userErrors{
                        field
                        message
                    }
                }
            }
        Query;

    public static function formatVariables(string $id, string $reasonCode, $merchantMessage): array
    {
        return [
            "id" => $id,
            "reason" => [
                "code" => $reasonCode,
                "merchantMessage" => $merchantMessage,
            ]
        ];
    }
}
