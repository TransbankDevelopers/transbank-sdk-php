<?php

namespace Transbank\Utils;

use Transbank\Webpay\Options;

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
        Options::ENVIRONMENT_PRODUCTION => 'https://webpay3g.transbank.cl/',
        Options::ENVIRONMENT_INTEGRATION => 'https://webpay3gint.transbank.cl/',

    ];

    /**
     * @return string
     */
    public static function getApiKey()
    {
        return static::$apiKey;
    }

    /**
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        static::$apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public static function getCommerceCode()
    {
        return static::$commerceCode;
    }

    /**
     * @param string $commerceCode
     */
    public static function setCommerceCode($commerceCode)
    {
        static::$commerceCode = $commerceCode;
    }

    /**
     * @return string
     */
    public static function getIntegrationType()
    {
        return static::$integrationType;
    }

    /**
     * @param string $integrationType
     */
    public static function setIntegrationType($integrationType)
    {
        static::$integrationType = $integrationType;
    }

    public static function getIntegrationTypeUrl($integrationType = null)
    {
        if ($integrationType == null) {
            return static::$INTEGRATION_TYPES[static::$integrationType];
        }

        return static::$INTEGRATION_TYPES[$integrationType];
    }

    /**
     * @return HttpClient
     */
    public static function getHttpClient()
    {
        if (!isset(static::$httpClient) || static::$httpClient == null) {
            static::$httpClient = new HttpClient();
        }

        return static::$httpClient;
    }

    public function configureForIntegration($commerceCode, $apiKey)
    {
        static::setApiKey($apiKey);
        static::setCommerceCode($commerceCode);
        static::setIntegrationType(Options::ENVIRONMENT_INTEGRATION);
    }

    public static function configureForProduction($commerceCode, $apiKey)
    {
        static::setApiKey($apiKey);
        static::setCommerceCode($commerceCode);
        static::setIntegrationType(Options::ENVIRONMENT_PRODUCTION);
    }
}
