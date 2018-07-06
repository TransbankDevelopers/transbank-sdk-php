<?php
namespace Transbank;


/** aqui deberia cargar todo los utils y la api en general */


/**
 * Class OnePay
 *
 * @package Transbank
*/
class OnePay 
{
    public static $integrationType = "Test";

    public static $appKey;
    public static $callbackUrl;

    public static $serverBasePath;
    public static $scriptPath;
    public static $apiKey;
    public static $sharedSecret;

    /**
     * Return the API key used for requests
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to use for requests
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }
    /**
     * Return the app key used for requests
     */
    public static function getAppKey()
    {
        return self::$appKey;
    }
    /**
     * Set the app key used for requests
     */
    public static function setAppKey($appKey)
    {
        self::$appKey = $appKey;
    }
    /**
     * Return the callback url
     */
    public static function getCallbackUrl()
    {
        return self::$callbackUrl;
    }
    /**
     * Set the callback url
     */
    public static function setCallbackUrl($callbackUrl)
    {
        self::$callbackUrl = $callbackUrl;
    }
    /**
     * Return the callback url
     */
    public static function getSharedSecret()
    {
        return self::$sharedSecret;
    }
    /**
     * Set the callback url
     */
    public static function setSharedSecret($sharedSecret)
    {
        self::$sharedSecret = $sharedSecret;
    }
}