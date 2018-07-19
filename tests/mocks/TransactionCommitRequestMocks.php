<?php
namespace Transbank;

class TransactionCommitRequestMocks {

    public static $transactionCommitRequestMocks = array();

    public static function transactionCreateRequestMocks()
    {
        if(empty(self::$transactionCommitRequestMocks)) {
            $cart = ShoppingCartMocks::get();
            $transactionCommitRequest = OnePayRequestBuilder::getInstance()
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

# {"occ":"1807419329781765","externalUniqueNumber":"8934751b-aa9a-45be-b686-1f45b6c45b02","issuedAt":1531401184,"signature":"wiY+BvD5dwduDWVkQ4FWkOkFir+fUndRMCVc8dgxUBk=","apiKey":"mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg","appKey":"04533c31-fe7e-43ed-bbc4-1c8ab1538afp"}