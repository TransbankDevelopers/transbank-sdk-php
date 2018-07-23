<?php
namespace Transbank;
/**
 * @class TransactionCreateRequest
 *  Creates a request object to be used when connecting to OnePay
 * 
 * @package Transbank
 */

 class OnePayRequestBuilder {
    // Make this be a singleton class
    protected static $instance = null;
    protected function __construct() { }
    protected function __clone() { }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function buildCreateRequest($shoppingCart, $options = null)
    {
        $options = self::buildOptions($options);
        $issuedAt = time();
        $externalUniqueNumber = (int)(microtime(true) * 1000);
        $request = new TransactionCreateRequest(
                                          $externalUniqueNumber,
                                          $shoppingCart->getTotal(),
                                          $shoppingCart->getItemQuantity(),
                                          $issuedAt,
                                          $shoppingCart->getItems(),
                                          OnePay::getCallBackUrl(),
                                          'WEB'); # Channel, can be 'web' or 'mobile' for now

        self::setKeys($request, $options);
        return OnePaySignUtil::getInstance()->sign($request, $options->getSharedSecret());
    }

    public function buildCommitRequest($occ, $externalUniqueNumber, $options = null)
    {
        $options = self::buildOptions($options);

        $issuedAt = time();
        $request = new TransactionCommitRequest($occ, $externalUniqueNumber, $issuedAt);
        self::setKeys($request, $options);
        return OnePaySignUtil::getInstance()->sign($request, $options->getSharedSecret());
    }

    public function buildRefundRequest($refundAmount, $occ,
                                       $externalUniqueNumber,
                                       $authorizationCode,
                                       $options = null)
    {
        $options = self::buildOptions($options);
        $issuedAt = time();
        $request = new RefundCreateRequest($refundAmount,
                                           $occ,
                                           (string)$externalUniqueNumber,
                                           $authorizationCode,
                                           $issuedAt);
        self::setKeys($request, $options);
        return OnePaySignUtil::getInstance()->sign($request,
                                                   $options->getSharedSecret());
    }

    public static function buildOptions($options)
    {
        
        if (!$options)
        {
            return Options::getDefaults();
        }

        if (!$options->getApiKey()) {
            $options->setApiKey(OnePay::getApiKey());
        }

        if (!$options->getAppKey()) {
            $options->setAppKey(OnePay::getAppKey());
        }
        if (!$options->getSharedSecret()) {
            $options->setSharedSecret(OnePay::getSharedSecret());
        }
        return $options;
    }

    public static function setKeys($request, $options)
    {
        $request->setAppKey($options->getAppKey());
        $request->setApiKey($options->getApiKey());
    }


 }
