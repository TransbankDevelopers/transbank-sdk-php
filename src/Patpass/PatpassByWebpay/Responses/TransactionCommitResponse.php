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
    public function getBuyOrder()
    {
        return $this->buyOrder;
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
    public function getCardDetail()
    {
        return $this->cardDetail;
    }

    /**
     * @return mixed
     */
    public function getAccountingDate()
    {
        return $this->accountingDate;
    }

    /**
     * @return mixed
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
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
        return (int) $this->responseCode;
    }

    /**
     * @return mixed
     */
    public function getInstallmentsNumber()
    {
        return $this->installmentsNumber;
    }

}
