<?php

namespace Transbank\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class TransactionDetail extends \Transbank\Webpay\WebpayPlus\Responses\TransactionDetail
{
    public $prepaidBalance;

    public static function createFromArray(array $array)
    {
        $result = new TransactionDetail();
        $result->amount = Utils::returnValueIfExists($array, 'amount');
        $result->status = Utils::returnValueIfExists($array, 'status');
        $result->authorizationCode = Utils::returnValueIfExists($array, 'authorization_code');
        $result->paymentTypeCode = Utils::returnValueIfExists($array, 'payment_type_code');
        $result->responseCode = Utils::returnValueIfExists($array, 'response_code');
        $result->installmentsNumber = Utils::returnValueIfExists($array, 'installments_number');
        $result->installmentsAmount = Utils::returnValueIfExists($array, 'installments_amount');
        $result->commerceCode = Utils::returnValueIfExists($array, 'commerce_code');
        $result->buyOrder = Utils::returnValueIfExists($array, 'buy_order');
        $result->balance = Utils::returnValueIfExists($array, 'balance');
        $result->prepaidBalance = Utils::returnValueIfExists($array, 'prepaid_balance');

        return $result;
    }

    /**
     * @return mixed
     */
    public function getPrepaidBalance()
    {
        return $this->prepaidBalance;
    }

    /**
     * @param mixed $balance
     */
    public function setPrepaidBalance($prepaidBalance)
    {
        $this->prepaidBalance = $prepaidBalance;
    }


}
