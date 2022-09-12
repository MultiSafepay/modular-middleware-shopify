<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<table class="table-auto">
    <thead>
    <tr>
        <th class="px-6 py-4 text-left whitespace-nowrap">id</th>
        <th class="px-6 py-4 text-left whitespace-nowrap">shopify_domain</th>
        <th class="px-6 py-4 text-left whitespace-nowrap">shopify_access_token</th>
        <th class="px-6 py-4 text-left whitespace-nowrap">multisafepay_api_key</th>
        <th class="px-6 py-4 text-left whitespace-nowrap">multisafepay_environment</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($shops as $shop)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">{{$shop->id}}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{$shop->shopify_domain}}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{$shop->accessTokens->all()}}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{$shop->multisafepay_api_key}}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{$shop->multisafepay_environment}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
