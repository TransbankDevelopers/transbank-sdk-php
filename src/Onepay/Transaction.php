<?php
namespace Transbank\Onepay;
/**
 *  Class Transaction
 *  This class creates or commits a transaction (that is, a purchase);
 *
 * package @transbank;
 *
 */
use Transbank\Onepay\Exceptions\TransactionCreateException;
use Transbank\Onepay\Exceptions\TransactionCommitException;
use Transbank\Onepay\Exceptions\SignException;
use Transbank\Utils\HttpClient;

 class Transaction {
    const SEND_TRANSACTION = "sendtransaction";
    const COMMIT_TRANSACTION = "gettransactionnumber";
    const TRANSACTION_BASE_PATH = '/ewallet-plugin-api-services/services/transactionservice/';

    private static $httpClient = null;
    public static function getServiceUrl()
    {
        return OnepayBase::getIntegrationTypeUrl("TEST") . "/ewallet-plugin-api-services/services/transactionservice";
    }

    private static function getHttpClient()
    {
        if(!isset(self::$httpClient) || self::$httpClient == null) {
            self::$httpClient = new HttpClient();
        }
        return self::$httpClient;
    }

     /**
      * @param $shoppingCart
      * @param ChannelEnum|null $channel
      * @param null $externalUniqueNumber
      * @param null $options
      * @return TransactionCreateResponse
      * @throws SignException
      * @throws TransactionCreateException
      * @throws \Exception
      */
     public static function create($shoppingCart, $channel = null, $externalUniqueNumber = null, $options = null)
    {
        if ($channel instanceof Options) {
            $options = $channel;
            $channel = null;
        }

        if ($externalUniqueNumber instanceof Options){
            $options = $externalUniqueNumber;
            $externalUniqueNumber = null;
        }

        if (null != $channel && $channel == ChannelEnum::APP() && null == OnepayBase::getAppScheme())
            throw new TransactionCreateException('You need to set an appScheme if you want to use the APP channel');

        if (null != $channel && $channel == ChannelEnum::MOBILE() && null == OnepayBase::getCallbackUrl())
            throw new TransactionCreateException('You need to set a valid callback if you want to use the MOBILE channel');

        if(!$shoppingCart instanceof ShoppingCart) {
            throw new \Exception("Shopping cart is null or empty");
        }
        $http = self::getHttpClient();
        $options = OnepayRequestBuilder::getInstance()->buildOptions($options);
        $request = json_encode(OnepayRequestBuilder::getInstance()->buildCreateRequest($shoppingCart, $channel, $externalUniqueNumber, $options), JSON_UNESCAPED_SLASHES);
        $path = self::TRANSACTION_BASE_PATH . self::SEND_TRANSACTION;

        $httpResponse = $http->post(OnepayBase::getCurrentIntegrationTypeUrl(), $path ,$request);
        if ($httpResponse === null) {
            throw new TransactionCreateException('Could not obtain a response from the service', -1);
        }
        
        $httpCode = $httpResponse->getStatusCode();
        $responseJson = json_decode($httpResponse->getBody(), true);

        if ($httpCode != 200 && $httpCode != 204) {
            throw new TransactionCreateException('Could not obtain a response from the service', -1);
        }
        if($responseJson['responseCode'] != "OK") {
            throw new TransactionCreateException($responseJson['responseCode'] . " : " . $responseJson['description'], -1);
        }

        $transactionCreateResponse =  new TransactionCreateResponse($responseJson);
        
        $signatureIsValid = OnepaySignUtil::getInstance()
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
        $options = OnepayRequestBuilder::getInstance()->buildOptions($options);
        $request = json_encode(OnepayRequestBuilder::getInstance()->buildCommitRequest($occ, $externalUniqueNumber, $options), JSON_UNESCAPED_SLASHES);
        $path = self::TRANSACTION_BASE_PATH . self::COMMIT_TRANSACTION;

        $httpResponse = $http->post(OnepayBase::getCurrentIntegrationTypeUrl(), $path ,$request);
        if ($httpResponse === null) {
            throw new TransactionCommitException('Could not obtain a response from the service', -1);
        }
        $httpCode = $httpResponse->getStatusCode();
        $responseJson = json_decode($httpResponse->getBody(), true);

        if ($httpCode != 200 && $httpCode != 204) {
            throw new TransactionCommitException('Could not obtain a response from the service', -1);
        }
        if($responseJson['responseCode'] != "OK") {
            throw new TransactionCommitException($responseJson['responseCode'] . " : " . $responseJson['description'], -1);
        }

        $transactionCommitResponse = new TransactionCommitResponse($responseJson);
        $signatureIsValid = OnepaySignUtil::getInstance()
                                          ->validate($transactionCommitResponse,
                                                     $options->getSharedSecret());
        if (!$signatureIsValid) {
            throw new SignException('The response signature is not valid', -1);
        }
        return $transactionCommitResponse;
    }
 }
