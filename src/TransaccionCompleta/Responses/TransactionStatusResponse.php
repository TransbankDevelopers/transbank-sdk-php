<?php

namespace Transbank\TransaccionCompleta\Responses;

use Transbank\Utils\HasTransactionStatus;
use Transbank\Utils\Utils;

class TransactionStatusResponse
{
    use HasTransactionStatus;

    public $vci;
    public $prepaidBalance;

    public function __construct($json)
    {
        $this->vci = Utils::returnValueIfExists($json, 'vci');
        $this->setTransactionStatusFields($json);
        $this->prepaidBalance = Utils::returnValueIfExists($json, 'prepaid_balance');
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
     /**
     * @return mixed
     */
    public function getPrepaidBalance()
    {
        return $this->prepaidBalance;
    }

    /**
     * @param mixed $prepaidBalance
     *
     * @return TransactionStatusResponse
     */
    public function setPrepaidBalance($prepaidBalance)
    {
        $this->prepaidBalance = $prepaidBalance;

        return $this;
    }
}
