<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;

/**
 * Class MallTransactionStatusResponse.
 */
class MallTransactionStatusResponse
{
    public ?string $vci;
    public ?string $buyOrder;
    public ?string $sessionId;
    public ?string $cardNumber;
    public ?array $cardDetail;
    public ?string $expirationDate;
    public ?string $accountingDate;
    public ?string $transactionDate;
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
     * @return ?string
     */
    public function getBuyOrder(): ?string
    {
        return $this->buyOrder;
    }

    /**
     * @return ?string
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
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
    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
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
     * @return ?TransactionDetail[]
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @return ?string
     */
    public function getVci(): ?string
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
