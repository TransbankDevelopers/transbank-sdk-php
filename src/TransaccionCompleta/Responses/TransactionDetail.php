<?php

namespace Transbank\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class TransactionDetail extends \Transbank\Webpay\WebpayPlus\Responses\TransactionDetail
{
    public $prepaidBalance;

    public static function createFromArray(array $array)
    {
        $result = parent::createFromArray($array);
        $result->prepaidBalance = Utils::returnValueIfExists($array, 'prepaid_balance');


        return $result;
    }
}
