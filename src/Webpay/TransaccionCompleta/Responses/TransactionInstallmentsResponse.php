<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class TransactionInstallmentsResponse
{
    public ?float $installmentsAmount;
    public string $idQueryInstallments;
    public array $deferredPeriods;

    public function __construct(array $json)
    {
        $this->installmentsAmount = Utils::returnValueIfExists($json, 'installments_amount');
        $this->idQueryInstallments = Utils::returnValueIfExists($json, 'id_query_installments');
        $this->deferredPeriods = Utils::returnValueIfExists($json, 'deferred_periods');
    }

    /**
     * @return ?float
     */
    public function getInstallmentsAmount(): float
    {
        return $this->installmentsAmount;
    }

    /**
     * @return string
     */
    public function getIdQueryInstallments(): string
    {
        return $this->idQueryInstallments;
    }

    /**
     * @return array
     */
    public function getDeferredPeriods(): array
    {
        return $this->deferredPeriods;
    }
}
