<?php


namespace Transbank\Webpay\Oneclick;


class MallTransactionStatusResponse
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

        $buyOrder = isset($json["buy_order"]) ? $json["buy_order"] : null;
        $this->setBuyOrder($buyOrder);

        $sessionId = isset($json["session_id"]) ? $json["session_id"] : null;
        $this->setSessionId($sessionId);

        $cardNumber = isset($json["card_detail"]) ? isset($json["card_detail"]["card_number"]) ? $json["card_detail"]["card_number"] : null : null;
        $this->setCardNumber($cardNumber);

        $expirationDate = isset($json["expiration_date"]) ? $json["expiration_date"] : null;
        $this->setExpirationDate($expirationDate);

        $accountingDate = isset($json["accounting_date"]) ? $json["accounting_date"] : null;
        $this->setAccountingDate($accountingDate);

        $transactionDate = isset($json["transaction_date"]) ? $json["transaction_date"] : null;
        $this->setTransactionDate($transactionDate);

        $details = isset($json["details"]) ? $json["details"] : null;
        $this->setDetails($details);
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
     * @return MallTransactionStatusResponse
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
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
     * @return MallTransactionStatusResponse
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
     * @return MallTransactionStatusResponse
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
     * @return MallTransactionStatusResponse
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
     * @return MallTransactionStatusResponse
     */
    public function setAccountingDate($accountingDate)
    {
        $this->accountingDate = $accountingDate;
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
     * @return MallTransactionStatusResponse
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
     * @return MallTransactionStatusResponse
     */
    public function setBuyOrder($buyOrder)
    {
        $this->buyOrder = $buyOrder;
        return $this;
    }

}
