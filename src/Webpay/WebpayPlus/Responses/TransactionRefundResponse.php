<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;
use Transbank\Utils\TransactionStatusEnum;

class TransactionRefundResponse
{
    /**
     * @var string
     */
    public string|null $type;
    /**
     * @var string|null
     */
    public string|null $authorizationCode;
    /**
     * @var string|null
     */
    public string|null $authorizationDate;
    /**
     * @var int|float|null
     */
    public int|float|null $nullifiedAmount;
    /**
     * @var int|float|null
     */
    public int|float|null $balance;
    /**
     * @var int|null
     */
    public int|null $responseCode;

    /**
     * TransactionRefundResponse constructor.
     *
     * @param array $json
     */
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
     * @return bool
     */
    public function success(): bool
    {
        return $this->getType() === TransactionStatusEnum::STATUS_REVERSED ||
            ($this->getType() === TransactionStatusEnum::STATUS_NULLIFIED && $this->getResponseCode() === 0);
    }

    /**
     * @return int|float|null
     */
    public function getNullifiedAmount(): int|float|null
    {
        return $this->nullifiedAmount;
    }

    /**
     * @return int|float|null
     */
    public function getBalance(): int|float|null
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
}
