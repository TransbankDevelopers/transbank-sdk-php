<?php


namespace Transbank\Webpay\Oneclick;


class Transaction
{

    const AUTHORIZE_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/oneclick/v1.0/transaction';
    const TRANSACTION_STATUS_ENDPONT = '/rswebpaytransaction/api/oneclick/v1.0/transactions/$BUYORDER$';
    const TRANSACTION_REFUND_ENDPOINT = '/rswebpaytransaction/api/oneclick/v1.0/transactions/$BUYORDER$/refund';

    public static function authorize($userName, $tbkUser, $amount, $buyOrder,
                                     $options = null)
    {


    }

    public static function getStatus($buyOrder, $options = null)
    {

    }

    public static function refund($buyOrder, $amount, $options = null)
    {

    }


}
