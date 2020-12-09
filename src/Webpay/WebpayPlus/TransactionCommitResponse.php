<?php

namespace Transbank\Webpay\WebpayPlus;

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
    public $cardDetail;
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

    public function __construct($json)
    {
        $this->vci = isset($json["vci"]) ? $json["vci"] : null;
        $this->amount = isset($json["amount"]) ? $json["amount"] : null;
        $this->status = isset($json["status"]) ? $json["status"] : null;
        $this->buyOrder = isset($json["buy_order"]) ? $json["buy_order"] : null;
        $this->sessionId = isset($json["session_id"]) ?$json["session_id"] : null;
        $this->cardDetail = isset($json["card_detail"]) ? $json["card_detail"] : null;
        $this->accountingDate = isset($json["accounting_date"]) ? $json["accounting_date"] : null;
        $this->transactionDate = isset($json["transaction_date"]) ? $json["transaction_date"] : null;
        $this->authorizationCode = isset($json["authorization_code"]) ? $json["authorization_code"] : null;
        $this->paymentTypeCode = isset($json["payment_type_code"]) ? $json["payment_type_code"] : null;
        $this->responseCode = isset($json["response_code"]) ? $json["response_code"] : null;
        $this->installmentsAmount = isset($json["installments_amount"]) ? $json["installments_amount"] : null;
        $this->installmentsNumber = isset($json["installments_number"]) ? $json["installments_number"] : null;
        $this->balance = isset($json["balance"]) ? $json["balance"] : null;
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
    public function getCardDetail()
    {
        return $this->cardDetail;
    }

    /**
     * @param $cardDetail
     * @return TransactionCommitResponse
     */
    public function setCardDetail($cardDetail)
    {
        $this->cardDetail = $cardDetail;
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
