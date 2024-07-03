<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class MallTransactionRefundResponse
{
    public string|null $type;
    public string|null $authorizationCode;
    public string|null $authorizationDate;
    public ?float $nullifiedAmount;
    public ?float $balance;
    public int|null $responseCode;

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
     * @return string|null
     */
    public function getType(): string|null
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationCode(): string|null
    {
        return $this->authorizationCode;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationDate(): string|null
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
     * @return int|null
     */
    public function getResponseCode(): int|null
    {
        return $this->responseCode;
    }
}
