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

    public function build($shoppingCart, $options)
    {
        if (!$options) 
        {
            $options = self::buildOptions($options);
        }

        $issuedAt = time();
        $request = new TransactionCreateRequest(
                                          Uuid::v4(),
                                          $shoppingCart->getTotal(),
                                          $shoppingCart->getItemQuantity(),
                                          $issuedAt,
                                          $shoppingCart->getItems(),
                                          OnePay::getCallBackUrl(),
                                          'WEB'); # Channel, can be 'web' or 'mobile' for now
        $request->setApiKey($options->getApiKey());
        $request->setAppKey($options->getAppKey());

        echo "shared secret is", "\n";
        var_dump($options->getSharedSecret());
        return OnePaySignUtil::getInstance()->sign($request, $options->getSharedSecret());
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


 }