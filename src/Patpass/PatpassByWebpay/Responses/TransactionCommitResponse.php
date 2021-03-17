<?php

namespace Transbank\Patpass\PatpassByWebpay\Responses;

class TransactionCommitResponse
{
    private $vci;
    private $amount;
    private $status;
    private $buyOrder;
    private $sessionId;
    private $cardDetail;
    private $accountingDate;
    private $transactionDate;
    private $authorizationCode;
    private $paymentTypeCode;
    private $responseCode;
    private $installmentsNumber;

    /**
     * TransactionCommitResponse constructor.
     */
    public function __construct($json)
    {
        $this->setVci($this->returnValueIfExists($json, 'vci'));
        $this->setAmount($this->returnValueIfExists($json, 'amount'));
        $this->setStatus($this->returnValueIfExists($json, 'status'));
        $this->setBuyOrder($this->returnValueIfExists($json, 'buy_order'));
        $this->setSessionId($this->returnValueIfExists($json, 'session_id'));
        $this->setCardDetail($this->returnValueIfExists($json, 'card_detail'));
        $this->setAccountingDate($this->returnValueIfExists($json, 'accounting_date'));
        $this->setTransactionDate($this->returnValueIfExists($json, 'transaction_date'));
        $this->setAuthorizationCode($this->returnValueIfExists($json, 'authorization_code'));
        $this->setPaymentTypeCode($this->returnValueIfExists($json, 'payment_type_code'));
        $this->setResponseCode($this->returnValueIfExists($json, 'response_code'));
        $this->setInstallmentsNumber($this->returnValueIfExists($json, 'installments_number'));
    }

    public function returnValueIfExists($json, $key)
    {
        return isset($json[$key]) ? $json[$key] : null;
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
     * @param mixed $cardNumber
     *
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
        return (int) $this->responseCode;
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
}
