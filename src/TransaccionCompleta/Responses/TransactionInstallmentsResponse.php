<?php

namespace Transbank\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class TransactionInstallmentsResponse
{
    public $installmentsAmount;
    public $idQueryInstallments;
    public $deferredPeriods;

    public function __construct($json)
    {
        $this->installmentsAmount = Utils::returnValueIfExists($json, 'installments_amount');
        $this->idQueryInstallments = Utils::returnValueIfExists($json, 'id_query_installments');
        $this->deferredPeriods = Utils::returnValueIfExists($json, 'deferred_periods');
    }

    /**
     * @return mixed
     */
    public function getInstallmentsAmount()
    {
        return $this->installmentsAmount;
    }

    /**
     * @param mixed $installmentsAmount
     *
     * @return TransactionInstallmentsResponse
     */
    public function setInstallmentsAmount($installmentsAmount)
    {
        $this->installmentsAmount = $installmentsAmount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdQueryInstallments()
    {
        return $this->idQueryInstallments;
    }

    /**
     * @param mixed $idQueryInstallments
     *
     * @return TransactionInstallmentsResponse
     */
    public function setIdQueryInstallments($idQueryInstallments)
    {
        $this->idQueryInstallments = $idQueryInstallments;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeferredPeriods()
    {
        return $this->deferredPeriods;
    }

    /**
     * @param mixed $deferredPeriods
     *
     * @return TransactionInstallmentsResponse
     */
    public function setDeferredPeriods($deferredPeriods)
    {
        $this->deferredPeriods = $deferredPeriods;

        return $this;
    }
}
