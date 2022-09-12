<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API\Request;


use ModularMultiSafepay\ModularMultiSafepay\Order\CustomerInfo;
use ModularMultiSafepay\ModularMultiSafepay\Order\DeliveryInfo;
use ModularMultiSafepay\ModularMultiSafepay\Order\PaymentOptions;
use Illuminate\Support\Facades\URL;

final class Payment
{
    private array $data;
    private string $domain;

    public function __construct(array $data, string $domain)
    {
        $this->data = $data;
        $this->domain = $domain;
    }

    public function getGateway(): string
    {
        return '';
    }

    public function getAmountInCents(): float
    {
        return (float)$this->data['amount'] * 100;
    }

    public function getCurrency(): string
    {
        return $this->data['currency'];
    }

    public function getOrderId(): string
    {
        return $this->data['id'];
    }

    public function getDescription(): string
    {
        return $this->data['id'] . ' for ' . $this->data['customer']['billing_address']['given_name'] . ' ' . $this->data['customer']['billing_address']['family_name'];
    }

    public function getCustomerInfo(): CustomerInfo
    {
        $locale = str_contains($this->data['customer']['locale'], '-') ?
            str_replace('-','_', $this->data['customer']['locale']) :
            $this->data['customer']['locale'] . '_' . $this->data["customer"]["billing_address"]["country_code"];

        $address = $this->parse($this->data['customer']['billing_address']['line1']);
        $customer = new CustomerInfo(
            $this->data['customer']['billing_address']['given_name'] ?? '',
            $this->data['customer']['billing_address']['family_name'],
            '',
            $this->data['customer']['phone_number'] ?? '',
            $this->data['customer']['email'] ?? '',
            '',
            $address[0],
            $address[1],
            $this->data['customer']['billing_address']['postal_code'] ?? '',
            $this->data['customer']['billing_address']['city'],
            $this->data['customer']['billing_address']['country_code'],
            $locale
        );

        return $customer;
    }

    public function getShippingInfo(): DeliveryInfo
    {
        if (!isset($this->data['customer']['shipping_address'])) {
            return new DeliveryInfo();
        }
        $address = $this->parse($this->data['customer']['shipping_address']['line1']);

        $locale = str_contains($this->data['customer']['locale'], '-') ?
            str_replace('-','_', $this->data['customer']['locale']) :
            $this->data['customer']['locale'] . '_' . $this->data['customer']['shipping_address']['country_code'];

        $delivery = new DeliveryInfo(
            $this->data['customer']['shipping_address']['given_name'] ?? '',
            $this->data['customer']['shipping_address']['family_name'],
            $address[0],
            $address[1],
            $this->data['customer']['shipping_address']['postal_code'] ?? '',
            $this->data['customer']['shipping_address']['city'],
            $this->data['customer']['shipping_address']['country_code'],
            $locale
        );

        return $delivery;
    }

    public function getPaymentOptions(): PaymentOptions
    {
        return new PaymentOptions(
            URL::signedRoute('shopify.redirect', [
                'id' => $this->getOrderId(),
                'domain' => urlencode($this->domain),
            ]),
            $this->getCancelUrl(),
            route('shopify.notification'),
            true,
            true
        );
    }

    public function getCancelUrl(): string
    {
        return $this->data['payment_method']['data']['cancel_url'];
    }

    /**
     * GraphQL identifier for payment
     */
    public function getGid(): string
    {
        return $this->data['gid'];
    }

    public function isAuthorization(): bool
    {
        return $this->data['kind'] === 'authorization';
    }

    /*
     * https://github.com/MultiSafepay/php-sdk/blob/314fb031cd3f68e44a866bc6e309fe11e31de655/src/ValueObject/Customer/AddressParser.php
     */
    protected function parse(string $address1, string $address2 = ''): array
    {
        // Remove whitespaces from the beginning and end
        $fullAddress = trim("$address1 $address2");

        // Turn multiple whitespaces into one single whitespace
        $fullAddress = preg_replace('/[[:blank:]]+/', ' ', $fullAddress);

        // Split the address into 3 groups: street, apartment and extension
        $pattern = '/(.+?)\s?([\d]+[\S]*)((\s?[A-z])*?)$/';
        preg_match($pattern, $fullAddress, $matches);

        if (!$matches) {
            return [$fullAddress, ''];
        }

        return $this->extractStreetAndApartment(
            $matches[1] ?? '',
            $matches[2] ?? '',
            $matches[3] ?? ''
        );
    }

    /*
     * https://github.com/MultiSafepay/php-sdk/blob/314fb031cd3f68e44a866bc6e309fe11e31de655/src/ValueObject/Customer/AddressParser.php
     */
    protected function extractStreetAndApartment(string $group1, string $group2, string $group3): array
    {
        if (is_numeric($group1) && is_numeric($group2)) {
            return [trim($group3), trim($group1 . $group2)];
        }

        return [trim($group1), trim($group2 . $group3)];
    }
}
