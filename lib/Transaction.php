<?php
namespace Transbank;
/**
 *  Class Transaction
 * 
 */

 /** Esto debe tener Channel (cliente HTTP) para poder conectarse a servicios */
 class Transaction {

    const SERVICE_URI =  "TODO: ONEPAY_INTEGRATION_TYPE_URL" . "/ewallet-plugin-api-services/services/transactionservice";
    const SEND_TRANSACTION = "sendtransaction";

    public static function create($shoppingCart, $options = null) {
        if(!$shoppingCart instanceof ShoppingCart) {
            throw new \Exception("Shopping cart is null or empty");
        }

        $http = new HttpClient();
        $request = json_encode(OnePayRequestBuilder::getInstance()->build($shoppingCart, $options));
        return json_decode($http->post('host', 'path', $request), true);
    }

 }
