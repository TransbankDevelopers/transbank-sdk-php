<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\TransactionStatusEnum;

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

    /**
     * TransactionDetail constructor.
     *
     * @param $amount
     * @param $status
     * @param $authorizationCode
     * @param $paymentTypeCode
     * @param $responseCode
     * @param $installmentsNumber
     * @param $commerceCode
     * @param $buyOrder
     */
    public function __construct(
        $amount,
        $status,
        $authorizationCode,
        $paymentTypeCode,
        $responseCode,
        $installmentsNumber,
        $installmentsAmount,
        $commerceCode,
        $buyOrder
    ) {
        $this->amount = $amount;
        $this->status = $status;
        $this->authorizationCode = $authorizationCode;
        $this->paymentTypeCode = $paymentTypeCode;
        $this->responseCode = $responseCode;
        $this->installmentsNumber = $installmentsNumber;
        $this->installmentsAmount = $installmentsAmount;
        $this->commerceCode = $commerceCode;
        $this->buyOrder = $buyOrder;
    }

    public static function createFromArray(array $array)
    {
        $amount = isset($array['amount']) ? $array['amount'] : null;
        $status = isset($array['status']) ? $array['status'] : null;
        $authorizationCode = isset($array['authorization_code']) ? $array['authorization_code'] : null;
        $paymentTypeCode = isset($array['payment_type_code']) ? $array['payment_type_code'] : null;
        $responseCode = isset($array['response_code']) ? $array['response_code'] : null;
        $installmentsNumber = isset($array['installments_number']) ? $array['installments_number'] : null;
        $installmentsAmount = isset($array['installments_amount']) ? $array['installments_amount'] : null;
        $commerceCode = isset($array['commerce_code']) ? $array['commerce_code'] : null;
        $buyOrder = isset($array['buy_order']) ? $array['buy_order'] : null;

        return new static($amount, $status, $authorizationCode, $paymentTypeCode, $responseCode, $installmentsNumber,
            $installmentsAmount, $commerceCode, $buyOrder);
    }

    public function isApproved()
    {
        return $this->getResponseCode() === ResponseCodesEnum::RESPONSE_CODE_APPROVED &&
            !in_array($this->getStatus(), [TransactionStatusEnum::STATUS_FAILED]);
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param mixed $authorizationCode
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
    }

    /**
     * @return mixed
     */
    public function getPaymentTypeCode()
    {
        return $this->paymentTypeCode;
    }

    /**
     * @param mixed $paymentTypeCode
     */
    public function setPaymentTypeCode($paymentTypeCode)
    {
        $this->paymentTypeCode = $paymentTypeCode;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return mixed
     */
    public function getInstallmentsNumber()
    {
        return $this->installmentsNumber;
    }

    /**
     * @param mixed $installmentsNumber
     */
    public function setInstallmentsNumber($installmentsNumber)
    {
        $this->installmentsNumber = $installmentsNumber;
    }

    /**
     * @return mixed
     */
    public function getCommerceCode()
    {
        return $this->commerceCode;
    }

    /**
     * @param mixed $commerceCode
     */
    public function setCommerceCode($commerceCode)
    {
        $this->commerceCode = $commerceCode;
    }

    /**
     * @return mixed
     */
    public function getBuyOrder()
    {
        return $this->buyOrder;
    }

    /**
     * @param mixed $buyOrder
     */
    public function setBuyOrder($buyOrder)
    {
        $this->buyOrder = $buyOrder;
    }

    /**
     * @return mixed
     */
    public function getInstallmentsAmount()
    {
        return $this->installmentsAmount;
    }

    /**
     * @param mixed $installmentsAmount
     */
    public function setInstallmentsAmount($installmentsAmount)
    {
        $this->installmentsAmount = $installmentsAmount;
    }
}
