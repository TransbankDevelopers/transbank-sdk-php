<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;

/**
 * Class MallTransactionStatusResponse.
 */
class MallTransactionStatusResponse
{
    public string|null $vci;
    public string|null $buyOrder;
    public string|null $sessionId;
    public string|null $cardNumber;
    public ?array $cardDetail;
    public string|null $expirationDate;
    public string|null $accountingDate;
    public string|null $transactionDate;
    public ?array $details;

    /**
     * @var TransactionDetail[]
     */
    public function __construct(array $json)
    {
        $this->vci = Utils::returnValueIfExists($json, 'vci');
        $this->buyOrder = Utils::returnValueIfExists($json, 'buy_order');
        $this->sessionId = Utils::returnValueIfExists($json, 'session_id');
        $this->cardDetail = Utils::returnValueIfExists($json, 'card_detail');
        $this->cardNumber = Utils::returnValueIfExists($this->cardDetail, 'card_number');
        $this->expirationDate = Utils::returnValueIfExists($json, 'expiration_date');
        $this->accountingDate = Utils::returnValueIfExists($json, 'accounting_date');
        $this->transactionDate = Utils::returnValueIfExists($json, 'transaction_date');

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
    public function getBuyOrder(): string|null
    {
        return $this->buyOrder;
    }

    /**
     * @return string|null
     */
    public function getSessionId(): string|null
    {
        return $this->sessionId;
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
    public function getExpirationDate(): string|null
    {
        return $this->expirationDate;
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
     * @return ?TransactionDetail[]
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @return string|null
     */
    public function getVci(): string|null
    {
        return $this->vci;
    }

    /**
     * @return ?array
     */
    public function getCardDetail(): ?array
    {
        return $this->cardDetail;
    }
}
