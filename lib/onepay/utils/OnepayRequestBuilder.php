<?php
namespace Transbank\Onepay;
/**
 * @class TransactionCreateRequest
 *  Creates a request object to be used when connecting to Onepay
 * 
 * @package Transbank
 */

 class OnepayRequestBuilder {
    // Make this be a singleton class
    protected static $instance = null;
    protected function __construct() { }
    protected function __clone() { }


     /**
      * @return OnepayRequestBuilder singleton;
      */
     public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function buildCreateRequest($shoppingCart, $channel, $externalUniqueNumber = null, $options = null)
    {
        if (null == OnepayBase::getCallBackUrl()) {
            OnepayBase::setCallbackUrl(OnepayBase::DEFAULT_CALLBACK);
        }

        if (null == $channel) {
            $channel = OnepayBase::DEFAULT_CHANNEL();
        }

        if (null == $externalUniqueNumber){
            $externalUniqueNumber = (int)(microtime(true) * 1000);
        }

        $options = self::buildOptions($options);
        $issuedAt = time();

        $request = new TransactionCreateRequest(
                                          $externalUniqueNumber,
                                          $shoppingCart->getTotal(),
                                          $shoppingCart->getItemQuantity(),
                                          $issuedAt,
                                          $shoppingCart->getItems(),
                                          OnepayBase::getCallBackUrl(),
                                          $channel, # Channel, can be 'WEB', 'MOBILE' or 'APP'
                                          OnepayBase::getAppScheme(),
                                          $options->getQrWidthHeight(),
                                          $options->getCommerceLogoUrl());

        self::setKeys($request, $options);
        return OnepaySignUtil::getInstance()->sign($request, $options->getSharedSecret());
    }

    public function buildCommitRequest($occ, $externalUniqueNumber, $options = null)
    {
        $options = self::buildOptions($options);

        $issuedAt = time();
        $request = new TransactionCommitRequest($occ, $externalUniqueNumber, $issuedAt);
        self::setKeys($request, $options);
        return OnepaySignUtil::getInstance()->sign($request, $options->getSharedSecret());
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
        return OnepaySignUtil::getInstance()->sign($request,
                                                   $options->getSharedSecret());
    }


     public static function buildOptions($options)
    {
        
        if (!$options)
        {
            return Options::getDefaults();
        }

        if (!$options->getApiKey()) {
            $options->setApiKey(OnepayBase::getApiKey());
        }

        if (!$options->getAppKey()) {
            $options->setAppKey(OnepayBase::getCurrentIntegrationTypeAppKey());
        }
        if (!$options->getSharedSecret()) {
            $options->setSharedSecret(OnepayBase::getSharedSecret());
        }
        return $options;
    }

    public static function setKeys($request, $options)
    {
        $request->setAppKey($options->getAppKey());
        $request->setApiKey($options->getApiKey());
    }


 }
