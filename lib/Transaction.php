<?php
namespace Transbank;
/**
 *  Class Transaction
 *  This class creates or commits a transaction (that is, a purchase);
 * 
 * package @transbank;
 * 
 */
use Transbank\OnePay\Exceptions\TransactionCreateException;
use Transbank\OnePay\Exceptions\TransactionCommitException;
use Transbank\OnePay\Exceptions\SignException;

 class Transaction {
    const SEND_TRANSACTION = "sendtransaction";
    const COMMIT_TRANSACTION = "gettransactionnumber";
    const TRANSACTION_BASE_PATH = '/ewallet-plugin-api-services/services/transactionservice/';

    private static $httpClient = null;
    public static function getServiceUrl()
    {
        return OnePay::getIntegrationTypeUrl("TEST") . "/ewallet-plugin-api-services/services/transactionservice";
    }

    private static function getHttpClient()
    {
        if(!isset(self::$httpClient) || self::$httpClient == null) {
            self::$httpClient = new HttpClient();
        }
        return self::$httpClient;
    }

    public static function create($shoppingCart, $options = null)
    {
        if(!$shoppingCart instanceof ShoppingCart) {
            throw new \Exception("Shopping cart is null or empty");
        }
        $http = self::getHttpClient();
        $options = OnePayRequestBuilder::getInstance()->buildOptions($options);
        $request = json_encode(OnePayRequestBuilder::getInstance()->buildCreateRequest($shoppingCart, $options), JSON_UNESCAPED_SLASHES);
        $path = self::TRANSACTION_BASE_PATH . self::SEND_TRANSACTION;
        $httpResponse = json_decode($http->post(OnePay::getCurrentIntegrationTypeUrl(), $path ,$request), true);

        if (!$httpResponse) {
            throw new TransactionCreateException('Could not obtain a response from the service', -1);
        }
        if($httpResponse['responseCode'] != "OK") {
            throw new TransactionCreateException($httpResponse['responseCode'] . " : " . $httpResponse['description'], -1);
        }

        $transactionCreateResponse =  new TransactionCreateResponse($httpResponse);
        
        $signatureIsValid = OnePaySignUtil::getInstance()
                            ->validate($transactionCreateResponse,
                                        $options->getSharedSecret());
        if (!$signatureIsValid) {
            throw new SignException('The response signature is not valid.', -1);
        }
        return $transactionCreateResponse;
    }

    public static function commit($occ, $externalUniqueNumber, $options = null)
    {
        $http = self::getHttpClient();
        $request = json_encode(OnePayRequestBuilder::getInstance()->buildCommitRequest($occ, $externalUniqueNumber, $options), JSON_UNESCAPED_SLASHES);
        $path = self::TRANSACTION_BASE_PATH . self::COMMIT_TRANSACTION;
        $httpResponse = json_decode($http->post(OnePay::getCurrentIntegrationTypeUrl(), $path, $request), true);

        if (!$httpResponse) {
            throw new TransactionCommitException('Could not obtain a response from the service', -1);
        }
        if($httpResponse['responseCode'] != "OK") {
            throw new TransactionCommitException($httpResponse['responseCode'] . " : " . $httpResponse['description'], -1);
        }

        $transactionCommitResponse = new TransactionCommitResponse($httpResponse);
        $signatureIsValid = OnePaySignUtil::getInstance()
                                          ->validate($transactionCommitResponse,
                                                     $options->getSharedSecret());
        if (!$signatureIsValid) {
            throw new SignException('The response signature is not valid', -1);
        }
        return $transactionCommitResponse;
    }
 }
