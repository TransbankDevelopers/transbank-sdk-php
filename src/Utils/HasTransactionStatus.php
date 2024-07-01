<?php

namespace Transbank\Utils;

trait HasTransactionStatus
{
    public string $status;
    public int $responseCode;
    public float $amount;
    public string $authorizationCode;
    public string $paymentTypeCode;
    public string $accountingDate;
    public int $installmentsNumber;
    public ?float $installmentsAmount;
    public string $sessionId;
    public string $buyOrder;
    public string $cardNumber;
    public array $cardDetail;
    public string $transactionDate;
    public ?float $balance;

    /**
     * @return ?float
     */
    public function getBalance(): ?float
    {
        return $this->balance;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode(): string
    {
        return $this->authorizationCode;
    }

    /**
     * @return int
     */
    public function getInstallmentsNumber(): int
    {
        return $this->installmentsNumber;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @return string
     */
    public function getBuyOrder(): string
    {
        return $this->buyOrder;
    }

    /**
     * @return string
     */
    public function getAccountingDate(): string
    {
        return $this->accountingDate;
    }

    /**
     * @param array $json
     */
    public function setTransactionStatusFields(array $json): void
    {
        $this->amount =  Utils::returnValueIfExists($json, 'amount');
        $this->status = Utils::returnValueIfExists($json, 'status');
        $this->buyOrder = Utils::returnValueIfExists($json, 'buy_order');
        $this->sessionId = Utils::returnValueIfExists($json, 'session_id');
        $this->cardDetail = Utils::returnValueIfExists($json, 'card_detail');
        $this->cardNumber = Utils::returnValueIfExists($this->cardDetail, 'card_number');
        $this->accountingDate = Utils::returnValueIfExists($json, 'accounting_date');
        $this->transactionDate = Utils::returnValueIfExists($json, 'transaction_date');
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->paymentTypeCode = Utils::returnValueIfExists($json, 'payment_type_code');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
        $this->installmentsAmount = Utils::returnValueIfExists($json, 'installments_amount');
        $this->installmentsNumber = Utils::returnValueIfExists($json, 'installments_number');
        $this->balance = Utils::returnValueIfExists($json, 'balance');
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
    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    /**
     * @return ?string
     */
    public function getPaymentTypeCode(): ?string
    {
        return $this->paymentTypeCode;
    }

    /**
     * @return ?float
     */
    public function getInstallmentsAmount(): ?float
    {
        return $this->installmentsAmount;
    }

    /**
     * @return ?string
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @return ?float
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @return ?string
     */
    public function getTransactionDate(): ?string
    {
        return $this->transactionDate;
    }
}
