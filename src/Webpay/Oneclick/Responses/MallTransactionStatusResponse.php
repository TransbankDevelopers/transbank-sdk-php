<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class MallTransactionStatusResponse
{
    public ?string $buyOrder;
    public ?array $cardDetail;
    public ?string $cardNumber;
    public ?string $accountingDate;
    public ?string $transactionDate;
    public ?array $details;

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
     * @return ?string
     */
    public function getTransactionDate(): ?string
    {
        return $this->transactionDate;
    }

    /**
     * @return ?string
     */
    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    /**
     * @return ?string
     */
    public function getAccountingDate(): ?string
    {
        return $this->accountingDate;
    }

    /**
     * @return ?TransactionDetail[]
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @return ?string
     */
    public function getBuyOrder(): ?string
    {
        return $this->buyOrder;
    }
}
