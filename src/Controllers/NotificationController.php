<?php declare(strict_types=1);

namespace ModularShopify\ModularShopify\Controllers;

use ModularMultiSafepay\ModularMiddlewareMultiSafepay\MultiSafepay;
use App\Http\Controllers\Controller;
use App\Jobs\Shopify\NotificationJob;
use App\Models\Shopify;
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
                ->onQueue('notifications-low')
                ->delay(now()->addHours(2));

            Log::info('Dispatching notification for ' . $gid,
                [
                    'event' => 'notification_dispatch',
                    'notification' => $request->all()
                ]);

            return response('OK');
        }

        NotificationJob::dispatch($gid, $shop, $order)
            ->onQueue('notifications-high');
        Log::info('Dispatching completed notification for ' . $gid,
            [
                'event' => 'notification_dispatch',
                'notification' => $request->all()
            ]);

        return response('OK');
    }
}
