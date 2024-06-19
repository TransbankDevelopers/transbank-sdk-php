<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

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
     * @return mixed
     */
    public function getPrepaidBalance()
    {
        return $this->prepaidBalance;
    }

}
