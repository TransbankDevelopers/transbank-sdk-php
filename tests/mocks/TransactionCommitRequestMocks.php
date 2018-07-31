<?php
namespace Transbank\Onepay;

class TransactionCommitRequestMocks {

    public static $transactionCommitRequestMocks = array();

    public static function transactionCreateRequestMocks()
    {
        if(empty(self::$transactionCommitRequestMocks)) {
            $cart = ShoppingCartMocks::get();
            $transactionCommitRequest = OnepayRequestBuilder::getInstance()
                                        ->buildCommitRequest("1807419329781765",
                                                             "8934751b-aa9a-45be-b686-1f45b6c45b02",
                                                             null);
            array_push(self::$transactionCommitRequestMocks, $transactionCommitRequest);
        }
        return self::$transactionCommitRequestMocks;
    }

    public static function get($indexOfMock = 0)
    {
        return self::transactionCreateRequestMocks()[$indexOfMock];
    }
}
