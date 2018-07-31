<?php
namespace Transbank\Onepay;
require_once(__DIR__ . '/ShoppingCartMocks.php');

class TransactionCreateRequestMocks {

    public static $transactionCreateRequestMocks = array();

    public static function transactionCreateRequestMocks()
    {
        if(empty(self::$transactionCreateRequestMocks)) {
            $cart = ShoppingCartMocks::get();
            $transactionCreateRequest = OnepayRequestBuilder::getInstance()->buildCreateRequest($cart, null);
            array_push(self::$transactionCreateRequestMocks, $transactionCreateRequest);
        }
        return self::$transactionCreateRequestMocks;
    }

    public static function get($indexOfMock = 0)
    {
        return self::transactionCreateRequestMocks()[$indexOfMock];
    }
}
