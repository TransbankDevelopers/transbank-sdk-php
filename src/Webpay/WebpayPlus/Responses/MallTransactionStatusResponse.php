<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

/**
 * Class MallTransactionStatusResponse.
 */
class MallTransactionStatusResponse
{
    public $vci;
    public $buyOrder;
    public $sessionId;
    public $cardNumber;
    public $cardDetail;
    public $expirationDate;
    public $accountingDate;
    public $transactionDate;

    /**
     * @var TransactionDetail[]
     */
    public $details;

    public function __construct($json)
    {
        $this->vci = isset($json['vci']) ? $json['vci'] : null;
        $this->buyOrder = isset($json['buy_order']) ? $json['buy_order'] : null;
        $this->sessionId = isset($json['session_id']) ? $json['session_id'] : null;
        $this->cardDetail = isset($json['card_detail']) ? $json['card_detail'] : null;
        $this->cardNumber = isset($json['card_detail']['card_number']) ? $json['card_detail']['card_number'] : null;
        $this->expirationDate = isset($json['expiration_date']) ? $json['expiration_date'] : null;
        $this->accountingDate = isset($json['accounting_date']) ? $json['accounting_date'] : null;
        $this->transactionDate = isset($json['transaction_date']) ? $json['transaction_date'] : null;
        $details = isset($json['details']) ? $json['details'] : null;
        $this->details = null;

        if (is_array($details)) {
            $this->details = [];
            foreach ($details as $detail) {
                $this->details[] = TransactionDetail::createFromArray($detail);
            }
        }
    }

    /**
     * If at least one of the child transactions is approved, the transaction is considered approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        if (!$details = $this->getDetails()) {
            return false;
        }

        foreach ($details as $detail) {
            if ($detail->isApproved()) {
                return true;
            }
        }

        return false;
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
     * @return TransactionDetail[]|null
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param $details
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
    public function getVci()
    {
        return $this->vci;
    }

    /**
     * @param mixed $vci
     */
    public function setVci($vci)
    {
        $this->vci = $vci;
    }

    /**
     * @return mixed|null
     */
    public function getCardDetail()
    {
        return $this->cardDetail;
    }

    /**
     * @param mixed|null $cardDetail
     */
    public function setCardDetail($cardDetail)
    {
        $this->cardDetail = $cardDetail;
    }
}
