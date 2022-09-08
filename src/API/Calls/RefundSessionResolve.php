<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API\Calls;


final class RefundSessionResolve
{
    public const QUERY = <<<Query
            mutation refundSessionResolve(\$id: ID!) {
              refundSessionResolve(id: \$id) {
                refundSession {
                  id
                }
                userErrors {
                  code
                  field
                  message
                }
              }
            }
        Query;

    public static function formatVariables(string $id): array
    {
        return [
            "id" => $id,
        ];
    }
}
