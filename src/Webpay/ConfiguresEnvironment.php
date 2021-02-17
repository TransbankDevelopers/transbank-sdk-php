<?php

namespace Transbank\Webpay;

use Transbank\Utils\HttpClient;

trait ConfiguresEnvironment
{
    /**
     * @var HttpClient|null
     */
    public static $httpClient = null;

    /**
     * @var array contains key-value pairs of
     *            integration_type => url_of_that_integration
     */
    public static $INTEGRATION_TYPES = [
        Options::ENVIRONMENT_LIVE => 'https://webpay3g.transbank.cl/',
        Options::ENVIRONMENT_TEST => 'https://webpay3gint.transbank.cl/',
        Options::ENVIRONMENT_MOCK => '',

    ];

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

    public static function getIntegrationTypeUrl($integrationType = null)
    {
        if ($integrationType == null) {
            return self::$INTEGRATION_TYPES[self::$integrationType];
        }

        return self::$INTEGRATION_TYPES[$integrationType];
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

    public function configureForIntegration($commerceCode, $apiKey)
    {
        self::setApiKey($apiKey);
        self::setCommerceCode($commerceCode);
        self::setIntegrationType(Options::ENVIRONMENT_TEST);
    }

    public static function configureForProduction($commerceCode, $apiKey)
    {
        self::setApiKey($apiKey);
        self::setCommerceCode($commerceCode);
        self::setIntegrationType(Options::ENVIRONMENT_LIVE);
    }
}
