<?php


namespace Transbank\Webpay\WebpayPlus;

class TransactionMallStatusResponse
{
    public $buyOrder;
    public $sessionId;
    public $cardNumber;
    public $expirationDate;
    public $accountingDate;
    public $transactionDate;
    public $details;

    public function __construct($json)
    {
        $this->buyOrder = isset($json["buy_order"]) ? $json["buy_order"] : null;
        $this->sessionId = isset($json["session_id"]) ?$json["session_id"] : null;
        $this->cardNumber = isset($json["card_detail"]) ? (isset($json["card_detail"]["card_number"]) ? $json["card_detail"]["card_number"] : null) : null;
        $this->expirationDate = isset($json["expiration_date"]) ? $json["expiration_date"] : null;
        $this->accountingDate = isset($json["accounting_date"]) ? $json["accounting_date"] : null;
        $this->transactionDate = isset($json["transaction_date"]) ? $json["transaction_date"] : null;
        $this->details = isset($json["details"]) ? $json["details"] : null;
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
     * @return TransactionMallStatusResponse
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
     * @return TransactionMallStatusResponse
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
     * @return TransactionMallStatusResponse
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param mixed $expirationDate
     *
     * @return TransactionMallStatusResponse
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
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
     * @return TransactionMallStatusResponse
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
     * @return TransactionMallStatusResponse
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     *
     * @return TransactionMallStatusResponse
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }
}
