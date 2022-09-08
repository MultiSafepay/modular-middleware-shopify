<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API;


final class Refund
{
    private string $domain;
    private string $transactionId;
    private string $refundId;
    private string $refundGid;
    private int $amount;
    private string $currency;

    public function __construct(string $domain, string $transactionId, string $refundId, string $refundGid, int $amount, string $currency)
    {
        $this->domain = $domain;
        $this->transactionId = $transactionId;
        $this->refundId = $refundId;
        $this->refundGid = $refundGid;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getRefundId(): string
    {
        return $this->refundId;
    }

    public function getRefundGid(): string
    {
        return $this->refundGid;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function __toString(): string
    {
        return 'Refund: {' .
            ' Transaction ID: ' . $this->getTransactionId() . ',' .
            ' Refund ID: ' . $this->getRefundId() . ',' .
            ' Refund GID: ' . $this->getRefundGid() . ',' .
            ' Amount: ' . $this->getAmount() . ',' .
            ' Currency: ' . $this->getCurrency() . ',' .
            ' Domain: ' . $this->getDomain() . '}';
    }
}
