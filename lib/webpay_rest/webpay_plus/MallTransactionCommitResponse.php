<?php


namespace Transbank\Webpay\WebpayPlus;


class MallTransactionCommitResponse
{
    public $vci;
    public $details; # {"amount":1000,"status":"AUTHORIZED","authorization_code":"1213","payment_type_code":"VN","response_code":0,"installments_number":0,"commerce_code":"597055555537","buy_order":"123buyorder1"}
    public $buyOrder;
    public $sessionId;
    public $cardNumber;
    public $accountingDate;
    public $transactionDate;

    public function __construct($json)
    {
        $this->vci = isset($json["vci"]) ? $json["vci"] : null;
        $this->details = isset($json["details"]) ? $json["details"] : null;
        $this->buyOrder = isset($json["buy_order"]) ? $json["buy_order"] : null;
        $this->sessionId = isset($json["session_id"]) ?$json["session_id"] : null;
        $this->cardNumber = isset($json["card_detail"]) ? (isset($json["card_detail"]["card_number"]) ? $json["card_detail"]["card_number"] : null) : null;
        $this->accountingDate = isset($json["accounting_date"]) ? $json["accounting_date"] : null;
        $this->transactionDate = isset($json["transaction_date"]) ? $json["transaction_date"] : null;
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
     * @return MallTransactionCommitResponse
     */
    public function setVci($vci)
    {
        $this->vci = $vci;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param mixed $details
     *
     * @return MallTransactionCommitResponse
     */
    public function setDetails($details)
    {
        $this->details = $details;
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
     * @return MallTransactionCommitResponse
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
     * @return MallTransactionCommitResponse
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
     * @return MallTransactionCommitResponse
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
     * @return MallTransactionCommitResponse
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
     * @return MallTransactionCommitResponse
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }


}
