<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API\Calls;


final class PaymentsAppConfigure
{
    public const QUERY = <<<Query
        mutation PaymentsAppConfigure(\$externalHandle: String, \$ready: Boolean!) {
            paymentsAppConfigure(externalHandle: \$externalHandle, ready: \$ready) {
                userErrors{
                    field
                    message
                }
            }
        }
        Query;

    public static function formatVariables(bool $ready): array
    {
        return [
            "externalHandle" => config('app.name'),
            "ready" => $ready,
        ];
    }
}
