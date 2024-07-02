<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class MallTransactionStatusResponse
{
    protected ?string $buyOrder;
    protected ?array $cardDetail;
    protected ?string $cardNumber;
    protected ?string $accountingDate;
    protected ?string $transactionDate;
    protected ?array $details;

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
     * @return ?string
     */
    public function getBuyOrder(): ?string
    {
        return $this->buyOrder;
    }

    /**
     * @return ?array
     */
    public function getCardDetail(): ?array
    {
        return $this->cardDetail;
    }

    /**
     * @return ?string
     */
    public function getAccountingDate(): ?string
    {
        return $this->accountingDate;
    }

    /**
     * @return ?string
     */
    public function getTransactionDate(): ?string
    {
        return $this->transactionDate;
    }

    /**
     * @return ?array
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @return ?string
     */
    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }
}
