<?php

namespace Transbank\TransaccionCompleta\Responses;

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
        print_r($json);
        $this->buyOrder = $json['buy_order'] ?? null;
        $this->cardDetail = $json['card_detail'] ?? null;
        $this->cardNumber = $json['card_detail']['card_number'] ?? null;
        $this->accountingDate = $json['accounting_date'] ?? null;
        $this->transactionDate = $json['transaction_date'] ?? null;
        $details = $json['details'] ?? null;

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
