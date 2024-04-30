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
     * @return mixed
     */
    public function getBuyOrder()
    {
        return $this->buyOrder;
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
     * @return mixed
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

}
