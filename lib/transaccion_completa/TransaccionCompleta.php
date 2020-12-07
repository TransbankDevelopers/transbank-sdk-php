<?php

/**
 * Class TransaccionCompleta
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank;

use Transbank\Utils\HttpClient;
use Transbank\TransaccionCompleta\Options;

class TransaccionCompleta
{
    /**
     * @var array $INTEGRATION_TYPES contains key-value pairs of
     * integration_type => url_of_that_integration
     */
    public static $INTEGRATION_TYPES = [
        "LIVE" => "https://wwww.pagoautomaticocontarjetas.cl/",
        "TEST" => "https://webpay3gint.transbank.cl/",
        "MOCK" => ""
    ];
    /**
     * @var $httpClient HttpClient|null
     */
    public static $httpClient = null;
    private static $apiKey = Options::DEFAULT_API_KEY;
    private static $commerceCode = Options::DEFAULT_TRANSACCION_COMPLETA_COMMERCE_CODE;
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
     * @return mixed
     */
    public static function getCommerceCode()
    {
        return self::$commerceCode;
    }

    /**
     * @param mixed $commerceCode
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

    /**
     * @return HttpClient
     */
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

    public static function configureForTesting() {
        self::$apiKey = Options::DEFAULT_API_KEY;
        self::$commerceCode = Options::DEFAULT_TRANSACCION_COMPLETA_COMMERCE_CODE;
        self::$integrationType = Options::DEFAULT_INTEGRATION_TYPE;
    }

}
