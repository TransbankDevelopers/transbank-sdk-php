<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;
use Transbank\Utils\TransactionStatusEnum;

class TransactionRefundResponse
{
    /**
     * @var string
     */
    public ?string $type;
    /**
     * @var ?string
     */
    public ?string $authorizationCode;
    /**
     * @var ?string
     */
    public ?string $authorizationDate;
    /**
     * @var ?float
     */
    public ?float $nullifiedAmount;
    /**
     * @var ?float
     */
    public ?float $balance;
    /**
     * @var ?int
     */
    public ?int $responseCode;

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
}
