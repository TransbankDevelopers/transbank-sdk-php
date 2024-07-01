<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;
use Transbank\Webpay\WebpayPlus\Responses\TransactionDetail as BaseTransactionDetail;

class TransactionDetail extends BaseTransactionDetail
{
    public ?int $prepaidBalance;

    /**
     * Creates an instance of TransactionDetail from an array
     *
     * @param array $array
     *
     * @return TransactionDetail
     */
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
     * @return ?int
     */
    public function getPrepaidBalance(): ?int
    {
        return $this->prepaidBalance;
    }
}
