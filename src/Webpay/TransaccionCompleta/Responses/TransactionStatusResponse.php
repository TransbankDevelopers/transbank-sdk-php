<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\HasTransactionStatus;
use Transbank\Utils\Utils;

class TransactionStatusResponse
{
    use HasTransactionStatus;

    public string|null $vci;
    public int|float|null $prepaidBalance;

    public function __construct(array $json)
    {
        $this->vci = Utils::returnValueIfExists($json, 'vci');
        $this->setTransactionStatusFields($json);
        $this->prepaidBalance = Utils::returnValueIfExists($json, 'prepaid_balance');
    }

    /**
     * @return string|null
     */
    public function getVci(): string|null
    {
        return $this->vci;
    }

    /**
     * @return int|float|null
     */
    public function getPrepaidBalance(): int|float|null
    {
        return $this->prepaidBalance;
    }
}
