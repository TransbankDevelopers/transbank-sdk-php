<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\TransactionStatusEnum;
use Transbank\Utils\Utils;

class TransactionDetail
{
    public $amount;
    public $status;
    public $authorizationCode;
    public $paymentTypeCode;
    public $responseCode;
    public $installmentsNumber;
    public $installmentsAmount;
    public $commerceCode;
    public $buyOrder;
    public $balance;

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

    public function isApproved()
    {
        if($this->getResponseCode() !== ResponseCodesEnum::RESPONSE_CODE_APPROVED) {
            return false;
        }

        switch($this->getStatus()) {
            case TransactionStatusEnum::STATUS_CAPTURED:
            case TransactionStatusEnum::STATUS_REVERSED:
            case TransactionStatusEnum::STATUS_NULLIFIED:
            case TransactionStatusEnum::STATUS_AUTHORIZED:
            case TransactionStatusEnum::STATUS_PARTIALLY_NULLIFIED:
                return true;
            default :
                return false;
        }
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return mixed
     */
    public function getPaymentTypeCode()
    {
        return $this->paymentTypeCode;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @return mixed
     */
    public function getInstallmentsNumber()
    {
        return $this->installmentsNumber;
    }

    /**
     * @return mixed
     */
    public function getCommerceCode()
    {
        return $this->commerceCode;
    }

    /**
     * @return mixed
     */
    public function getBuyOrder()
    {
        return $this->buyOrder;
    }

    /**
     * @return mixed
     */
    public function getInstallmentsAmount()
    {
        return $this->installmentsAmount;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

}
