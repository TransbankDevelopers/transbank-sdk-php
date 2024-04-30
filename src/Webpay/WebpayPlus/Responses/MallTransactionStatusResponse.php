<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;

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
        $this->vci = Utils::returnValueIfExists($json, 'vci');
        $this->buyOrder = Utils::returnValueIfExists($json, 'buy_order');
        $this->sessionId = Utils::returnValueIfExists($json, 'session_id');
        $this->cardDetail = Utils::returnValueIfExists($json, 'card_detail');
        $this->cardNumber = Utils::returnValueIfExists($this->cardDetail, 'card_number');
        $this->expirationDate = Utils::returnValueIfExists($json, 'expiration_date');
        $this->accountingDate = Utils::returnValueIfExists($json, 'accounting_date');
        $this->transactionDate = Utils::returnValueIfExists($json, 'transaction_date');
        $details = Utils::returnValueIfExists($json, 'details');
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
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
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
     * @return TransactionDetail[]|null
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return mixed
     */
    public function getVci()
    {
        return $this->vci;
    }

    /**
     * @return mixed|null
     */
    public function getCardDetail()
    {
        return $this->cardDetail;
    }

}
