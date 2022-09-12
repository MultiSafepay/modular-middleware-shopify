<?php declare(strict_types=1);

namespace ModularShopify\ModularShopify\Controllers;

use ModularMultiSafepay\ModularMultiSafepay\MultiSafepay;
use App\Http\Controllers\Controller;
use ModularShopify\ModularShopify\Jobs\NotificationJob;
use ModularShopify\ModularShopify\Models\Shopify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __invoke(Request $request, Shopify $shop, MultiSafepay $multiSafepay)
    {
        $gid = $request->input('var2');
        $order = $request->all();

        if ($request->status !== 'completed') {
            NotificationJob::dispatch($gid, $shop, $order)
                ->onQueue('shopify-low')
                ->delay(now()->addHours(2));

            Log::info('Dispatching notification for ' . $gid,
                [
                    'event' => 'notification_dispatch',
                    'notification' => $request->all()
                ]);

            return response('OK');
        }

        NotificationJob::dispatch($gid, $shop, $order)
            ->onQueue('shopify-high');
        Log::info('Dispatching completed notification for ' . $gid,
            [
                'event' => 'notification_dispatch',
                'notification' => $request->all()
            ]);

        return response('OK');
    }
}
