<?php
namespace ModularShopify\ModularShopify\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{
    /**
     * @see https://shopify.dev/apps/auth/session-tokens/authenticate-an-embedded-app-using-session-tokens#make-sure-shop-records-are-updated
     * @see https://shopify.dev/apps/webhooks/configuring#respond-to-a-webhook
     */
    public function uninstall(Request $request)
    {
        //TODO mark shop as uninstalled
//        return response()->noContent(200);
        return;
    }
}
