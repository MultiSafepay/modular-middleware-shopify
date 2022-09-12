<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\Controllers;

use ModularShopify\ModularShopify\Jobs\SendMailJob;
use App\Mail\Shopify\GDPRMail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class GdprController extends Controller
{
    /**
     * @see https://shopify.dev/apps/webhooks/mandatory#customers-redact
     */
    public function customersRedact(Request $request): Response
    {
        Log::info('Received customers redact request', ['event' => 'customers_redact_request']);
        $this->sendMail(new GDPRMail($request->getContent(),'shopify.emails.gdpr.customers-redacted'));
        return response()->noContent(200);
    }

    /**
     * @see https://shopify.dev/apps/webhooks/mandatory#shop-redact
     */
    public function shopRedact(Request $request): Response
    {
        Log::info('Received shop redact request', ['event' => 'shop_redact_request']);
        $this->sendMail(new GDPRMail($request->getContent(),'shopify.emails.gdpr.shop-redacted'));
        return response()->noContent(200);
    }

    /**
     * @see https://shopify.dev/apps/webhooks/mandatory#customers-data_request
     */
    public function customersDataRequest(Request $request): Response
    {
        Log::info('Received customers data request', ['event' => 'customers_data_request']);
        $this->sendMail(new GDPRMail($request->getContent(),'shopify.emails.gdpr.customers-data-requested'));
        return response()->noContent(200);
    }

    private function sendMail(Mailable $mailable): void
    {
        $toEmail = config('mail.to.gdpr_address');

        Log::info('Sending GDPR email', ['event' => 'send_gdpr_email', 'to_email' => $toEmail]);

        SendMailJob::dispatch($toEmail, $mailable)->onQueue('gdpr');
    }
}
