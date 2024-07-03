<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\TransactionStatusEnum;
use Transbank\Utils\Utils;

class TransactionDetail
{
    public float|null $amount;
    public string|null $status;
    public string|null $authorizationCode;
    public string|null $paymentTypeCode;
    public int|null $responseCode;
    public int|null $installmentsNumber;
    public float|null $installmentsAmount;
    public string|null $commerceCode;
    public string|null $buyOrder;
    public float|null $balance;

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
     * @return float|null
     */
    public function getAmount(): float|null
    {
        return $this->amount;
    }

    /**
     * @return string|null
     */
    public function getStatus(): string|null
    {
        return $this->status;
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
    public function getPaymentTypeCode(): string|null
    {
        return $this->paymentTypeCode;
    }

    /**
     * @return int|null
     */
    public function getResponseCode(): int|null
    {
        return $this->responseCode;
    }

    /**
     * @return int|null
     */
    public function getInstallmentsNumber(): int|null
    {
        return $this->installmentsNumber;
    }

    /**
     * @return int|null
     */
    public function getCommerceCode(): string|null
    {
        return $this->commerceCode;
    }

    /**
     * @return string|null
     */
    public function getBuyOrder(): string|null
    {
        return $this->buyOrder;
    }

    /**
     * @return float|null
     */
    public function getInstallmentsAmount(): float|null
    {
        return $this->installmentsAmount;
    }

    /**
     * @return float|null
     */
    public function getBalance(): float|null
    {
        return $this->balance;
    }
}
