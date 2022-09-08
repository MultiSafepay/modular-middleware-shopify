<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API;


use App\Models\Shopify;
use App\Models\ShopifyAccessTokens;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GraphQLCall
{
    public const GRAPHQL_ENDPOINT = '/payments_apps/api/2022-04/graphql.json';

    public function __construct(private Shopify $shop)
    {
    }

    public function invoke(string $query, array $variables, $gateway): GraphQLResponse
    {
        $content = json_encode(['query' => $query, 'variables' => $variables], JSON_THROW_ON_ERROR);
        $url = $this->formatUrl();
        Log::info("GraphQL Query:", [$content]);
        try {
            Log::info('Invoking GraphQL call', ['url' => $url, 'content' . $content, 'event' => 'graphql_request']);
            $response = Http::withHeaders(['X-Shopify-Access-Token' => $this->getAccessToken($gateway)])
                ->withBody($content, 'application/json')
                ->post($url);

            Log::info('Response from GraphQL call', ['event' => 'graphql_response', 'body' => $response->body()]);
            $data = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

            return new GraphQLResponse($data);
        } catch (\Exception $exception) {
            Log::error('Error during GraphQL call', ['url' => $url, 'message' => $exception->getMessage(), 'event' => 'graphql_error']);
            return new GraphQLResponse([], false, $exception->getMessage());
        }
    }

    private function formatUrl(): string
    {
        return 'https://' . $this->shop->shopify_domain . self::GRAPHQL_ENDPOINT;
    }

    private function getAccessToken($gateway): string
    {
        $token = $this->shop->accessTokens->where('gateway', strtolower($gateway))->first()->shopify_access_token;
        return $token;
    }

    public static function execute(Shopify $shop, string $query, array $variables, $gateway): GraphQLResponse
    {
        return (new self($shop))->invoke($query, $variables, $gateway);
    }
}
