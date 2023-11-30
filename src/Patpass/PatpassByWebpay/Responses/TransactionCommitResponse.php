<?php

namespace Transbank\Patpass\PatpassByWebpay\Responses;

use Transbank\Utils\Utils;

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
        $this->vci = Utils::returnValueIfExists($json, 'vci');
        $this->amount = Utils::returnValueIfExists($json, 'amount');
        $this->status = Utils::returnValueIfExists($json, 'status');
        $this->buyOrder = Utils::returnValueIfExists($json, 'buy_order');
        $this->sessionId = Utils::returnValueIfExists($json, 'session_id');
        $this->cardDetail = Utils::returnValueIfExists($json, 'card_detail');
        $this->accountingDate = Utils::returnValueIfExists($json, 'accounting_date');
        $this->transactionDate = Utils::returnValueIfExists($json, 'transaction_date');
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->paymentTypeCode = Utils::returnValueIfExists($json, 'payment_type_code');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
        $this->installmentsNumber = Utils::returnValueIfExists($json, 'installments_number');
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
