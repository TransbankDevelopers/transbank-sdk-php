<?php

namespace Transbank\Webpay\Modal\Responses;

class TransactionCommitResponse
{
    public $amount;
    public $status;
    public $buyOrder;
    public $sessionId;
    public $cardDetail;
    public $cardNumber;
    public $accountingDate;
    public $transactionDate;
    public $authorizationCode;
    public $paymentTypeCode;
    public $responseCode;
    public $installmentsAmount;
    public $installmentsNumber;

    /**
     * TransactionCommitResponse constructor.
     *
     * @param mixed $json
     */
    public function __construct($json)
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
        $this->responseCode = (int) isset($json['response_code']) ? $json['response_code'] : null;
        $this->installmentsAmount = isset($json['installments_amount']) ? $json['installments_amount'] : null;
        $this->installmentsNumber = isset($json['installments_number']) ? $json['installments_number'] : null;
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
     * @return mixed|null
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
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
        return $this->responseCode;
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
    public function getInstallmentsNumber()
    {
        return $this->installmentsNumber;
    }
}
