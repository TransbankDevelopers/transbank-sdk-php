<?php

namespace Transbank\TransaccionCompleta\Responses;

use Transbank\Utils\HasTransactionStatus;
use Transbank\Utils\Utils;

class TransactionStatusResponse
{
    use HasTransactionStatus;

    public $vci;

    public function __construct($json)
    {
        $this->vci = Utils::returnValueIfExists($json, 'vci');
        $this->setTransactionStatusFields($json);
    }

    /**
     * @return mixed
     */
    public function getVci()
    {
        return $this->vci;
    }

    /**
     * @param mixed $vci
     *
     * @return TransactionStatusResponse
     */
    public function setVci($vci)
    {
        $this->vci = $vci;

        return $this;
    }
}
