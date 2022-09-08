<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API\Calls;


final class PaymentSessionPending
{
    public const QUERY = <<<Query
            mutation PaymentSessionPending(\$id: ID!, \$expires: DateTime!, \$reason: PaymentSessionStatePendingReason!) {
              paymentSessionPending(id: \$id, pendingExpiresAt: \$expires, reason: \$reason) {
                paymentSession {
                  id
                  state {
                    ... on PaymentSessionStatePending {
                      reason
                    }
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

    public static function formatVariables(string $id, string $reason): array
    {

        $dateTime = new \DateTime();
        $newDate = date_add($dateTime, date_interval_create_from_date_string('3 days'));
        $formatDate = $newDate->format(\DateTime::ATOM);

        return [
            "id" => $id,
            "expires" => $formatDate,
            "reason" => 'BUYER_ACTION_REQUIRED'
        ];
    }
}

