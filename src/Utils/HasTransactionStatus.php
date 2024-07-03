<?php

namespace Transbank\Utils;

trait HasTransactionStatus
{
    public string|null $status;
    public int|null $responseCode;
    public ?float $amount;
    public string|null $authorizationCode;
    public string|null $paymentTypeCode;
    public string|null $accountingDate;
    public int|null $installmentsNumber;
    public ?float $installmentsAmount;
    public string|null $sessionId;
    public string|null $buyOrder;
    public string|null $cardNumber;
    public array|null $cardDetail;
    public string|null $transactionDate;
    public ?float $balance;

    /**
     * @return ?float
     */
    public function getBalance(): ?float
    {
        return $this->balance;
    }

    /**
     * @return string|null
     */
    public function getStatus(): string|null
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationCode(): string|null
    {
        return $this->authorizationCode;
    }

    /**
     * @return int|null
     */
    public function getInstallmentsNumber(): int|null
    {
        return $this->installmentsNumber;
    }

    /**
     * @return int|null
     */
    public function getResponseCode(): int|null
    {
        return $this->responseCode;
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
    public function getAccountingDate(): string|null
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
     * @return array|null
     */
    public function getCardDetail(): array|null
    {
        return $this->cardDetail;
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
    public function getPaymentTypeCode(): string|null
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
     * @return string|null
     */
    public function getSessionId(): string|null
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
     * @return string|null
     */
    public function getTransactionDate(): string|null
    {
        return $this->transactionDate;
    }
}
