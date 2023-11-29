<?php

namespace Transbank\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class MallTransactionStatusResponse
{
    protected $buyOrder;
    protected $cardDetail;
    protected $cardNumber;
    protected $accountingDate;
    protected $transactionDate;
    protected $details;

    public function __construct($json)
    {
        $this->buyOrder = Utils::returnValueIfExists($json, 'buyOrder');
        $this->accountingDate = Utils::returnValueIfExists($json, 'accounting_date');
        $this->transactionDate = Utils::returnValueIfExists($json, 'transaction_date');
        $this->cardDetail = Utils::returnValueIfExists($json, 'card_detail');
        $this->cardNumber = Utils::returnValueIfExists($this->cardDetail, 'card_number');

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
     * @return mixed
     */
    public function getBuyOrder()
    {
        return $this->buyOrder;
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
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return mixed|null
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }
}
