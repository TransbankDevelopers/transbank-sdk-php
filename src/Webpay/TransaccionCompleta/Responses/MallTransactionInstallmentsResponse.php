<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class MallTransactionInstallmentsResponse
{
    public int|float|null $installmentsAmount;
    public string|null $idQueryInstallments;
    public array|null $deferredPeriods;

    public function __construct(array $json)
    {
        $this->installmentsAmount = Utils::returnValueIfExists($json, 'installments_amount');
        $this->idQueryInstallments = Utils::returnValueIfExists($json, 'id_query_installments');
        $this->deferredPeriods = Utils::returnValueIfExists($json, 'deferred_periods');
    }

    /**
     * @return int|float|null
     */
    public function getInstallmentsAmount(): int|float|null
    {
        return $this->installmentsAmount;
    }

    /**
     * @return string|null
     */
    public function getIdQueryInstallments(): string|null
    {
        return $this->idQueryInstallments;
    }

    /**
     * @return array|null
     */
    public function getDeferredPeriods(): array|null
    {
        return $this->deferredPeriods;
    }
}
