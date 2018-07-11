<?php
namespace Transbank;
/**
 *  Class Transaction
 * 
 */

 /** Esto debe tener Channel (cliente HTTP) para poder conectarse a servicios */
 class Transaction {
    const SEND_TRANSACTION = "sendtransaction";
    const COMMIT_TRANSACTION = "gettransactionnumber";

    public static function getServiceUrl()
    {
        return OnePay::getIntegrationTypeUrl("TEST") . "/ewallet-plugin-api-services/services/transactionservice";
    }

    public static function create($shoppingCart, $options = null)
    {
        if(!$shoppingCart instanceof ShoppingCart) {
            throw new \Exception("Shopping cart is null or empty");
        }
        $http = new HttpClient();

        $request = json_encode(OnePayRequestBuilder::getInstance()->build($shoppingCart, $options), JSON_UNESCAPED_SLASHES);
        $path = '/ewallet-plugin-api-services/services/transactionservice' . '/' . self::SEND_TRANSACTION;
        $response = $http->post(OnePay::getIntegrationTypeUrl("TEST"), $path ,$request);
        return json_decode($response, true);
    }

 }
