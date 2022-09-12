@component('mail::message')
    # Shopify Customers Data Request Received

    See https://shopify.dev/apps/webhooks/mandatory#customers-data_request

    ```json
    {!! $body !!}
    ```

    Sent from {{ config('app.name') }}
@endcomponent
