<?php declare(strict_types=1);

namespace ModularShopify\ModularShopify\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VoidController extends Controller
{
    /**
     * @see https://shopify.dev/apps/payments/voiding-an-authorized-payment#initiate-the-flow
     * Currently we don't implement this.
     */
    public function __invoke(Request $request)
    {
        Log::warning('Received void request', ['event' => 'void_request']);
        return response('', Response::HTTP_NOT_IMPLEMENTED);
    }
}
