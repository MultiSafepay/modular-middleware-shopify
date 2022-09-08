<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API\Calls;


final class PaymentSessionResolve
{
    public const QUERY = <<<Query
            mutation paymentSessionResolve(\$id: ID!, \$expires: DateTime) {
              paymentSessionResolve(id: \$id, authorizationExpiresAt: \$expires) {
                paymentSession {
                  id
                  status {
                    code
                  }
                nextAction {
                  action
                  context {
                    ... on PaymentSessionActionsRedirect {
                      redirectUrl
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

    public static function formatVariables(string $id): array
    {
        return [
            "id" => $id,
        ];
    }
}

