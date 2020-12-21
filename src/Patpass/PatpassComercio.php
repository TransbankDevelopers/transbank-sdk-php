<?php

/**
 * Class PatpassComercio
 *
 * @category
 * @package Transbank\Patpass\PatpassComercio
 *
 */


namespace Transbank\Patpass;

use Transbank\Utils\HttpClient;

class PatpassComercio
{
    public static $INTEGRATION_TYPES = [
        "LIVE" => "https://www.pagoautomaticocontarjetas.cl/",
        "TEST" => "https://pagoautomaticocontarjetasint.transbank.cl/",
        "MOCK" => ""
    ];

    public static $httpClient = null;
    private static $apiKey = Options::DEFAULT_PATPASS_COMERCIO_API_KEY;
    private static $commerceCode = Options::DEFAULT_PATPASS_COMERCIO_COMMERCE_CODE;
    private static $integrationType = Options::DEFAULT_INTEGRATION_TYPE;



    /**
     * @return string
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public static function getCommerceCode()
    {
        return self::$commerceCode;
    }

    /**
     * @param string $commerceCode
     */
    public static function setCommerceCode($commerceCode)
    {
        self::$commerceCode = $commerceCode;
    }

    /**
     * @return string
     */
    public static function getIntegrationType()
    {
        return self::$integrationType;
    }

    /**
     * @param string $integrationType
     */
    public static function setIntegrationType($integrationType)
    {
        self::$integrationType = $integrationType;
    }

    public static function getHttpClient()
    {
        if (!isset(self::$httpClient) || self::$httpClient == null) {
            self::$httpClient = new HttpClient();
        }
        return self::$httpClient;
    }

    public static function getIntegrationTypeUrl($integrationType = null)
    {
        if ($integrationType == null) {
            return self::$INTEGRATION_TYPES[self::$integrationType];
        }
        return self::$INTEGRATION_TYPES[$integrationType];
    }

    public static function configureForTesting()
    {
        self::setApiKey(Options::DEFAULT_PATPASS_COMERCIO_API_KEY);
        self::setCommerceCode(Options::DEFAULT_PATPASS_COMERCIO_COMMERCE_CODE);
        self::setIntegrationType("TEST");
    }
}
