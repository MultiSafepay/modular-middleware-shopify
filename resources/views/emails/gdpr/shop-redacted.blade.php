@component('mail::message')
    # Shopify Shop Redact Received

    See https://shopify.dev/apps/webhooks/mandatory#shop-redact

    ```json
    {!! $body !!}
    ```

    Sent from {{ config('app.name') }}
@endcomponent
