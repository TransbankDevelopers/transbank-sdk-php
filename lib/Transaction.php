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
        echo json_encode($shoppingCart->getItems(), true);
        echo json_encode(get_object_vars($shoppingCart), true);

        $request = json_encode(OnePayRequestBuilder::getInstance()->build($shoppingCart, $options));
        return json_decode($http->post(OnePay::getIntegrationTypeUrl("TEST"),"/ewallet-plugin-api-services/services/transactionservice" ,$request), true);
    }

 }
