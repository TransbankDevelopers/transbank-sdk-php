<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\HasTransactionStatus;
use Transbank\Utils\Utils;

class TransactionStatusResponse
{
    use HasTransactionStatus;

    public string|null $vci;
    public ?float $prepaidBalance;

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
     * @return ?float
     */
    public function getPrepaidBalance(): ?float
    {
        return $this->prepaidBalance;
    }
}
