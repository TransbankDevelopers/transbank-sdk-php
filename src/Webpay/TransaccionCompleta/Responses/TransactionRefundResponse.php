<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class TransactionRefundResponse
{
    public ?string $type;
    public ?string $authorizationCode;
    public ?string $authorizationDate;
    public ?float $nullifiedAmount;
    public ?float $balance;
    public ?int $responseCode;

    public function __construct(array $json)
    {
        $this->type = Utils::returnValueIfExists($json, 'type');
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->nullifiedAmount = Utils::returnValueIfExists($json, 'nullified_amount');
        $this->balance = Utils::returnValueIfExists($json, 'balance');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
    }

    /**
     * @return ?string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return ?string
     */
    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    /**
     * @return ?string
     */
    public function getAuthorizationDate(): ?string
    {
        return $this->authorizationDate;
    }

    /**
     * @return ?float
     */
    public function getNullifiedAmount(): ?float
    {
        return $this->nullifiedAmount;
    }

    /**
     * @return ?float
     */
    public function getBalance(): ?float
    {
        return $this->balance;
    }

    /**
     * @return ?int
     */
    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }
}
