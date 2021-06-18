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
        return (int) $this->responseCode;
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
        $this->amount = isset($json['amount']) ? $json['amount'] : null;
        $this->status = isset($json['status']) ? $json['status'] : null;
        $this->buyOrder = isset($json['buy_order']) ? $json['buy_order'] : null;
        $this->sessionId = isset($json['session_id']) ? $json['session_id'] : null;
        $this->cardDetail = isset($json['card_detail']) ? $json['card_detail'] : null;
        $this->cardNumber = isset($json['card_detail']['card_number']) ? $json['card_detail']['card_number'] : null;
        $this->accountingDate = isset($json['accounting_date']) ? $json['accounting_date'] : null;
        $this->transactionDate = isset($json['transaction_date']) ? $json['transaction_date'] : null;
        $this->authorizationCode = isset($json['authorization_code']) ? $json['authorization_code'] : null;
        $this->paymentTypeCode = isset($json['payment_type_code']) ? $json['payment_type_code'] : null;
        $this->responseCode = isset($json['response_code']) ? $json['response_code'] : null;
        $this->installmentsAmount = isset($json['installments_amount']) ? $json['installments_amount'] : null;
        $this->installmentsNumber = isset($json['installments_number']) ? $json['installments_number'] : null;
        $this->balance = isset($json['balance']) ? $json['balance'] : null;
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
