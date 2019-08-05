<?php

/**
 * Class TransactionInstallmentsResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


class TransactionInstallmentsResponse
{
    public $installmentsAmount;
    public $idQueryInstallments;
    public $deferredPeriods;


    public function __construct($json)
    {
        $installmentsAmount = isset($json["installments_amount"]) ? $json["installments_amount"] : null;
        $this->setInstallmentsAmount($installmentsAmount);
        $idQueryInstallments = isset($json["id_query_amount"]) ? $json["id_query_amount"] : null;
        $this->setIdQueryInstallments($idQueryInstallments);
        $deferredPeriods = isset($json["deferred_periods"]) ? $json["deferred_periods"] : null;
        $this->setDeferredPeriods($deferredPeriods);
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
     * @return TransactionInstallmentsResponse
     */
    public function setDeferredPeriods($deferredPeriods)
    {
        $this->deferredPeriods = $deferredPeriods;
        return $this;
    }



}
