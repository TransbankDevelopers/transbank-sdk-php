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
    const TRANSACTION_BASE_PATH = '/ewallet-plugin-api-services/services/transactionservice/';

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
        $options = OnePayRequestBuilder::getInstance()->buildOptions($options);
        $request = json_encode(OnePayRequestBuilder::getInstance()->buildCreateRequest($shoppingCart, $options), JSON_UNESCAPED_SLASHES);
        
        $path = self::TRANSACTION_BASE_PATH . self::SEND_TRANSACTION;
        $httpResponse = json_decode($http->post(OnePay::getCurrentIntegrationTypeUrl(), $path ,$request), true);

        if (!$httpResponse) {
            throw new TransactionCreateException(-1, 'Could not obtain a response from the service');
        }
        if($httpResponse['responseCode'] != "OK") {
            throw new TransactionCreateException(-1, $httpResponse['responseCode'] . " : " . $httpResponse['description']);
        }

        $transactionCreateResponse =  (new TransactionCreateResponse())
                                      ->fromJSON($httpResponse);
        $signatureIsValid = OnePaySignUtil::getInstance()
                            ->validate($transactionCreateResponse,
                                        $options->getSharedSecret());
        if (!$signatureIsValid) {
            throw new SignatureException(-1, "The response signature is not valid");
        }
        return $transactionCreateResponse;
    }

    public static function commit($occ, $externalUniqueNumber, $options)
    {
        $http = new HttpClient();
        $request = json_encode(OnePayRequestBuilder::getInstance()->buildCommitRequest($occ, $externalUniqueNumber, $options), JSON_UNESCAPED_SLASHES);
        $path = self::TRANSACTION_BASE_PATH . self::COMMIT_TRANSACTION;
        $httpResponse = $http->post(OnePay::getCurrentIntegrationTypeUrl(), $path, $request);
        return (new TransactionCommitResponse())->fromJSON($httpResponse);
    }
 }
