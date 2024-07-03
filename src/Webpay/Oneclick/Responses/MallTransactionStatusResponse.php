<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class MallTransactionStatusResponse
{
    public string|null $buyOrder;
    public array|null $cardDetail;
    public string|null $cardNumber;
    public string|null $accountingDate;
    public string|null $transactionDate;
    public array|null $details;

    public function __construct(array $json)
    {
        $this->buyOrder = Utils::returnValueIfExists($json, 'buy_order');
        $this->accountingDate = Utils::returnValueIfExists($json, 'accounting_date');
        $this->transactionDate = Utils::returnValueIfExists($json, 'transaction_date');
        $this->cardDetail = Utils::returnValueIfExists($json, 'card_detail');
        $this->cardNumber = Utils::returnValueIfExists($this->cardDetail, 'card_number');

        $this->details = [];
        if (is_array($json['details'])) {
            foreach ($json['details'] as $detail) {
                $this->details[] = TransactionDetail::createFromArray($detail);
            }
        }
    }

    /**
     * If at least one of the child transactions is approved, the transaction is considered approved.
     *
     * @return bool
     */
    public function isApproved(): bool
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
     * @return string|null
     */
    public function getTransactionDate(): string|null
    {
        return $this->transactionDate;
    }

    /**
     * @return string|null
     */
    public function getCardNumber(): string|null
    {
        return $this->cardNumber;
    }

    /**
     * @return string|null
     */
    public function getAccountingDate(): string|null
    {
        return $this->accountingDate;
    }

    /**
     * @return ?TransactionDetail[]
     */
    public function getDetails(): array|null
    {
        return $this->details;
    }

    /**
     * @return string|null
     */
    public function getBuyOrder(): string|null
    {
        return $this->buyOrder;
    }
}
