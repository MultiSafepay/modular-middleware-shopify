<?php

namespace ModularShopify\ModularShopify\API\Calls;

class WebhookSubscriptionCreate
{
    public const QUERY = <<<Query
          mutation webhookSubscriptionCreate(\$topic: WebhookSubscriptionTopic!, \$webhookSubscription: WebhookSubscriptionInput!) {
            webhookSubscriptionCreate(topic: \$topic, webhookSubscription: \$webhookSubscription) {
              webhookSubscription {
                id
                topic
                format
                endpoint {
                  __typename
                  ... on WebhookHttpEndpoint {
                    callbackUrl
                  }
                }
              }
            }
          }
        Query;

    public static function formatVariables(string $topic, $callbackUrl): array
    {
        return [
            "topic" => $topic,
            "webhookSubscription" => [
                "callbackUrl" => $callbackUrl,
                "format" => 'JSON',
            ],
        ];
    }
}
