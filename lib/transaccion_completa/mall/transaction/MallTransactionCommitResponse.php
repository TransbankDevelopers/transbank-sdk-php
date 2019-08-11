<?php

/**
 * Class MallTransactionCommitResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


class MallTransactionCommitResponse
{
    public $buyOrder;
    public $cardDetail;
    public $accountingDate;
    public $transactionDate;
    public $details;

    public function __construct($json)
    {
        $buyOrder = isset($json["buy_order"]) ? $json["buy_order"] : null;
        $this->setBuyOrder($buyOrder);
        $cardDetail = isset($json["card_detail"]) ? $json["card_detail"] : null;
        $this->setCardDetail($cardDetail);
        $accountingDate = isset($json["accounting_date"]) ? $json["accounting_date"] : null;
        $this->setAccountingDate($accountingDate);
        $details = isset($json["details"]) ? $json["details"] : null;
        $this->setDetails($details);


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
     */
    public function setBuyOrder($buyOrder)
    {
        $this->buyOrder = $buyOrder;
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
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
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
     */
    public function setDetails($details)
    {
        $this->details = $details;
        return $this;
    }




}
