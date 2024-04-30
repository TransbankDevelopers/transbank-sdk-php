<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class MallTransactionStatusResponse
{
    public $buyOrder;
    public $cardNumber;
    public $accountingDate;
    public $transactionDate;

    /**
     * @var TransactionDetail[]
     */
    public $details;

    public function __construct($json)
    {
        $this->buyOrder = Utils::returnValueIfExists($json, 'buy_order');
        $this->accountingDate = Utils::returnValueIfExists($json, 'accounting_date');
        $this->transactionDate = Utils::returnValueIfExists($json, 'transaction_date');
        $cardDetail = Utils::returnValueIfExists($json, 'card_detail');
        $this->cardNumber = Utils::returnValueIfExists($cardDetail, 'card_number');

        $details = Utils::returnValueIfExists($json, 'details');
        $detailsObjectArray = [];
        if (is_array($details)) {
            foreach ($details as $detail) {
                $detailsObjectArray[] = TransactionDetail::createFromArray($detail);
            }
        }
        $this->details = $detailsObjectArray;
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
    public function getTransactionDate()
    {
        return $this->transactionDate;
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
    public function getAccountingDate()
    {
        return $this->accountingDate;
    }

    /**
     * @return TransactionDetail[]
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return mixed
     */
    public function getBuyOrder()
    {
        return $this->buyOrder;
    }

}
