<?php

namespace Transbank\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class MallTransactionInstallmentsResponse
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
     * @return mixed
     */
    public function getIdQueryInstallments()
    {
        return $this->idQueryInstallments;
    }

    /**
     * @return mixed
     */
    public function getDeferredPeriods()
    {
        return $this->deferredPeriods;
    }

}
