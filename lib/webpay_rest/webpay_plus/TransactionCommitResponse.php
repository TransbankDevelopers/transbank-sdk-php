<?php

class TransactionCommitResponse
{
    /**
     * @var
     */
    public $vci;
    /**
     * @var
     */
    public $amount;
    /**
     * @var
     */
    public $status;
    /**
     * @var
     */
    public $buyOrder;
    /**
     * @var
     */
    public $sessionId;
    /**
     * @var
     */
    public $cardNumber;
    /**
     * @var
     */
    public $accountingDate;
    /**
     * @var
     */
    public $transactionDate;
    /**
     * @var
     */
    public $authorizationCode;
    /**
     * @var
     */
    public $paymentTypeCode;
    /**
     * @var
     */
    public $responseCode;
    /**
     * @var
     */
    public $installmentsAmount;
    /**
     * @var
     */
    public $installmentsNumber;
    /**
     * @var
     */
    public $balance;

    /**
     * TransactionCommitResponse constructor.
     *
     * @param $json
    *
     **/

    public function __construct($json) {
        $this->vci = $json["vci"];
        $this->amount = $json["amount"];
        $this->status = $json["status"];
        $this->buyOrder = $json["buy_order"];
        $this->sessionId = $json["session_id"];
        $this->cardNumber = $json["card_detail"]["card_number"];
        $this->accountingDate = $json["accounting_date"];
        $this->transactionDate = $json["transaction_date"];
        $this->authorizationCode = $json["authorization_code"];
        $this->paymentTypeCode = $json["payment_type_code"];
        $this->responseCode = $json["response_code"];
        $this->installmentsAmount = $json["installments_amount"];

        $this->installmentsNumber = $json["installmentsNumber"];
        $this->balance = $json["balance"];
    }

    /**
     * @return mixed
     */
    public function getVci()
    {
        return $this->vci;
    }

    /**
     * @param mixed $vci
     *
     * @return TransactionCommitResponse
     */
    public function setVci($vci)
    {
        $this->vci = $vci;
        return $this;
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
     *
     * @return TransactionCommitResponse
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
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
     *
     * @return TransactionCommitResponse
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * @param mixed $buyOrder
     *
     * @return TransactionCommitResponse
     */
    public function setBuyOrder($buyOrder)
    {
        $this->buyOrder = $buyOrder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param mixed $sessionId
     *
     * @return TransactionCommitResponse
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param mixed $cardNumber
     *
     * @return TransactionCommitResponse
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
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
     * @param mixed $accountingDate
     *
     * @return TransactionCommitResponse
     */
    public function setAccountingDate($accountingDate)
    {
        $this->accountingDate = $accountingDate;
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
     * @param mixed $transactionDate
     *
     * @return TransactionCommitResponse
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
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
     * @param mixed $authorizationCode
     *
     * @return TransactionCommitResponse
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
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
     * @param mixed $paymentTypeCode
     *
     * @return TransactionCommitResponse
     */
    public function setPaymentTypeCode($paymentTypeCode)
    {
        $this->paymentTypeCode = $paymentTypeCode;
        return $this;
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
     *
     * @return TransactionCommitResponse
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
        return $this;
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
     *
     * @return TransactionCommitResponse
     */
    public function setInstallmentsAmount($installmentsAmount)
    {
        $this->installmentsAmount = $installmentsAmount;
        return $this;
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
     *
     * @return TransactionCommitResponse
     */
    public function setInstallmentsNumber($installmentsNumber)
    {
        $this->installmentsNumber = $installmentsNumber;
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
     * @param mixed $balance
     *
     * @return TransactionCommitResponse
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }


}
