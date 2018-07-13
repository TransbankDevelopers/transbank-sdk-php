<?php
namespace Transbank;
/**
 *  Class Transaction
 *  This class creates or commits a transaction (that is, a purchase);
 * 
 * package @transbank;
 * 
 */
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

        $request = json_encode(OnePayRequestBuilder::getInstance()->buildCreateRequest($shoppingCart, $options), JSON_UNESCAPED_SLASHES);
        $path = '/ewallet-plugin-api-services/services/transactionservice' . '/' . self::SEND_TRANSACTION;
        $httpResponse = $http->post(OnePay::getCurrentIntegrationTypeUrl(), $path ,$request);
        return (new TransactionCreateResponse())->fromJSON($httpResponse);
    }

    public static function commit($occ, $externalUniqueNumber, $options)
    {
        $http = new HttpClient();
        $request = json_encode(OnePayRequestBuilder::getInstance()->buildCommitRequest($occ, $externalUniqueNumber, $options), JSON_UNESCAPED_SLASHES);
        $path = '/ewallet-plugin-api-services/services/transactionservice' . '/' . self::COMMIT_TRANSACTION;
        $httpResponse = $http->post(OnePay::getCurrentIntegrationTypeUrl(), $path, $request);
        return (new TransactionCommitResponse())->fromJSON($httpResponse);
    }
 }
