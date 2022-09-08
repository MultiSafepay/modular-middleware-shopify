<?php declare(strict_types=1);


namespace ModularShopify\ModularShopify\API;


class GraphQLResponse
{
    private array $data;
    private bool $hasSucceeded;
    private string $message;

    public function __construct(array $data, bool $hasSucceeded = true, string $message = 'success')
    {
        $this->data = $data;
        $this->hasSucceeded = $hasSucceeded;
        $this->message = $message;

    }

    public function getData(): array
    {
        return $this->data;
    }

    public function hasSucceeded(): bool
    {
        return $this->hasSucceeded;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
