<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API\Calls;


final class RefundSessionReject
{
    public const QUERY = <<<Query
            mutation RefundSessionReject(\$id: ID!, \$reason: RefundSessionRejectionReasonInput!) {
              refundSessionReject(id: \$id, reason: \$reason) {
                refundSession {
                  id
                  status {
                    code
                    reason {
                      ... on RefundSessionStatusReason {
                        code
                        merchantMessage
                      }
                    }
                  }
                }
                userErrors {
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
