<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\TransactionStatusEnum;
use Transbank\Utils\Utils;

class TransactionDetail
{
    public float $amount;
    public string $status;
    public string $authorizationCode;
    public string $paymentTypeCode;
    public int $responseCode;
    public int $installmentsNumber;
    public ?float $installmentsAmount;
    public ?string $commerceCode;
    public string $buyOrder;
    public ?float $balance;

    public static function createFromArray(array $array)
    {
        $result = new TransactionDetail();
        $result->amount = Utils::returnValueIfExists($array, 'amount');
        $result->status = Utils::returnValueIfExists($array, 'status');
        $result->authorizationCode = Utils::returnValueIfExists($array, 'authorization_code');
        $result->paymentTypeCode = Utils::returnValueIfExists($array, 'payment_type_code');
        $result->responseCode = Utils::returnValueIfExists($array, 'response_code');
        $result->installmentsNumber = Utils::returnValueIfExists($array, 'installments_number');
        $result->installmentsAmount = Utils::returnValueIfExists($array, 'installments_amount');
        $result->commerceCode = Utils::returnValueIfExists($array, 'commerce_code');
        $result->buyOrder = Utils::returnValueIfExists($array, 'buy_order');
        $result->balance = Utils::returnValueIfExists($array, 'balance');

        return $result;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        if ($this->getResponseCode() !== ResponseCodesEnum::RESPONSE_CODE_APPROVED) {
            return false;
        }

        switch ($this->getStatus()) {
            case TransactionStatusEnum::STATUS_CAPTURED:
            case TransactionStatusEnum::STATUS_REVERSED:
            case TransactionStatusEnum::STATUS_NULLIFIED:
            case TransactionStatusEnum::STATUS_AUTHORIZED:
            case TransactionStatusEnum::STATUS_PARTIALLY_NULLIFIED:
                return true;
            default:
                return false;
        }
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode(): string
    {
        return $this->authorizationCode;
    }

    /**
     * @return string
     */
    public function getPaymentTypeCode(): string
    {
        return $this->paymentTypeCode;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @return int
     */
    public function getInstallmentsNumber(): int
    {
        return $this->installmentsNumber;
    }

    /**
     * @return int
     */
    public function getCommerceCode(): string
    {
        return $this->commerceCode;
    }

    /**
     * @return string
     */
    public function getBuyOrder(): string
    {
        return $this->buyOrder;
    }

    /**
     * @return ?float
     */
    public function getInstallmentsAmount(): ?float
    {
        return $this->installmentsAmount;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }
}
