<?php

/**
 * Class TransactionCommitResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


use Transbank\Utils\Utils;

class TransactionCommitResponse
{
    public $vci;
    public $amount;
    public $status;
    public $buyOrder;
    public $sessionId;
    public $cardDetail; // card_number
    public $accountingDate;
    public $transactionDate;
    public $authorizationCode;
    public $paymentTypeCode;
    public $responseCode;
    public $installmentsNumber;
    public $installmentsAmount;

    public function __construct($json)
    {
        $vci =  Utils::returnValueIfExists($json, "vci");
        $this->setVci($vci);
        $amount =  Utils::returnValueIfExists($json, "amount");
        $this->setAmount($amount);
        $status =  Utils::returnValueIfExists($json, "status");
        $this->setStatus($status);
        $buyOrder =  Utils::returnValueIfExists($json, "buy_order");
        $this->setBuyOrder($buyOrder);
        $sessionId =  Utils::returnValueIfExists($json, "session_id");
        $this->setSessionId($sessionId);
        $cardDetail=  Utils::returnValueIfExists($json, "card_detail");
        $this->setCardDetail($cardDetail);
        $accountingDate =  Utils::returnValueIfExists($json, "accounting_date");
        $this->setAccountingDate($accountingDate);
        $transactionDate =  Utils::returnValueIfExists($json, "transaction_date");
        $this->setTransactionDate($transactionDate);
        $authorizationCode =  Utils::returnValueIfExists($json, "authorization_code");
        $this->setAuthorizationCode($authorizationCode);
        $paymentTypeCode =  Utils::returnValueIfExists($json, "payment_type_code");
        $this->setPaymentTypeCode($paymentTypeCode);
        $responseCode =  Utils::returnValueIfExists($json, "response_code");
        $this->setResponseCode($responseCode);
        $installmentsNumber =  Utils::returnValueIfExists($json, "installments_number");
        $this->setInstallmentsNumber($installmentsNumber);
        $installmentsAmount =  Utils::returnValueIfExists($json, "installments_amount");
        $this->setInstallmentsAmount($installmentsAmount);
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
     * @param mixed $cardDetail
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
    public function getInstallmentsAmount()
    {
        return $this->installmentsAmount;
    }

    /**
     * @param mixed $installmentsAmount
     * @return TransactionCommitResponse
     */
    public function setInstallmentsAmount($installmentsAmount)
    {
        $this->installmentsAmount = $installmentsAmount;
        return $this;
    }




}
