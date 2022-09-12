@component('mail::message')
    # Shopify Customers Redact Received

    See https://shopify.dev/apps/webhooks/mandatory#customers-redact

    ```json
    {!! $body !!}
    ```

    Sent from {{ config('app.name') }}
@endcomponent
