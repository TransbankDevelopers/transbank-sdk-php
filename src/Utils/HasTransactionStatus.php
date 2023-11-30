<?php

namespace Transbank\Utils;

trait HasTransactionStatus
{
    public $status;
    public $responseCode;
    public $amount;
    public $authorizationCode;
    public $paymentTypeCode;
    public $accountingDate;
    public $installmentsNumber;
    public $installmentsAmount;
    public $sessionId;
    public $buyOrder;
    public $cardNumber;
    public $cardDetail;
    public $transactionDate;
    public $balance;

    /**
     * @param mixed $amount
     *
     * @return static
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param mixed $buyOrder
     *
     * @return static
     */
    public function setBuyOrder($buyOrder)
    {
        $this->buyOrder = $buyOrder;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $cardNumber
     *
     * @return static
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
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
    public function getInstallmentsNumber()
    {
        return $this->installmentsNumber;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $status
     *
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param mixed $sessionId
     *
     * @return static
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @param mixed $paymentTypeCode
     *
     * @return static
     */
    public function setPaymentTypeCode($paymentTypeCode)
    {
        $this->paymentTypeCode = $paymentTypeCode;

        return $this;
    }

    /**
     * @param mixed $installmentsAmount
     *
     * @return static
     */
    public function setInstallmentsAmount($installmentsAmount)
    {
        $this->installmentsAmount = $installmentsAmount;

        return $this;
    }

    /**
     * @param mixed $accountingDate
     *
     * @return static
     */
    public function setAccountingDate($accountingDate)
    {
        $this->accountingDate = $accountingDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuyOrder()
    {
        return $this->buyOrder;
    }

    /**
     * @param mixed $responseCode
     *
     * @return static
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountingDate()
    {
        return $this->accountingDate;
    }

    /**
     * @param $json
     */
    public function setTransactionStatusFields($json)
    {
        $this->amount =  Utils::returnValueIfExists($json, 'amount');
        $this->status = Utils::returnValueIfExists($json, 'status');
        $this->buyOrder = Utils::returnValueIfExists($json, 'buy_order');
        $this->sessionId = Utils::returnValueIfExists($json, 'session_id');
        $this->cardDetail = Utils::returnValueIfExists($json, 'card_detail');
        $this->cardNumber = Utils::returnValueIfExists($this->cardDetail, 'card_number');
        $this->accountingDate = Utils::returnValueIfExists($json, 'accounting_date');
        $this->transactionDate = Utils::returnValueIfExists($json, 'transaction_date');
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->paymentTypeCode = Utils::returnValueIfExists($json, 'payment_type_code');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
        $this->installmentsAmount = Utils::returnValueIfExists($json, 'installments_amount');
        $this->installmentsNumber = Utils::returnValueIfExists($json, 'installments_number');
        $this->balance = Utils::returnValueIfExists($json, 'balance');

    }

    /**
     * @return mixed|null
     */
    public function getCardDetail()
    {
        return $this->cardDetail;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param mixed $transactionDate
     *
     * @return static
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
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
    public function getInstallmentsAmount()
    {
        return $this->installmentsAmount;
    }

    /**
     * @param mixed|null $cardDetail
     */
    public function setCardDetail($cardDetail)
    {
        $this->cardDetail = $cardDetail;
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $installmentsNumber
     *
     * @return static
     */
    public function setInstallmentsNumber($installmentsNumber)
    {
        $this->installmentsNumber = $installmentsNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @param mixed $balance
     *
     * @return static
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @param mixed $authorizationCode
     *
     * @return static
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }
}
