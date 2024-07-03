<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class MallTransactionStatusResponse
{
    protected string|null $buyOrder;
    protected array|null $cardDetail;
    protected string|null $cardNumber;
    protected string|null $accountingDate;
    protected string|null $transactionDate;
    protected array|null $details;

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
     * @return string|null
     */
    public function getBuyOrder(): string|null
    {
        return $this->buyOrder;
    }

    /**
     * @return array|null
     */
    public function getCardDetail(): array|null
    {
        return $this->cardDetail;
    }

    /**
     * @return string|null
     */
    public function getAccountingDate(): string|null
    {
        return $this->accountingDate;
    }

    /**
     * @return string|null
     */
    public function getTransactionDate(): string|null
    {
        return $this->transactionDate;
    }

    /**
     * @return array|null
     */
    public function getDetails(): array|null
    {
        return $this->details;
    }

    /**
     * @return string|null
     */
    public function getCardNumber(): string|null
    {
        return $this->cardNumber;
    }
}
