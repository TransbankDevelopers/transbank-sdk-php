<?php


namespace Transbank\Webpay;

use Transbank\Utils\HttpClient;

/**
 * Class WebpayPlus
 *
 * @package Transbank\Webpay
 *
 */
class WebpayPlus
{
    const ENVIRONMENT_LIVE = 'LIVE';
    const ENVIRONMENT_TEST = 'TEST';
    const ENVIRONMENT_MOCK = 'MOCK';


    /**
     * @var array $INTEGRATION_TYPES contains key-value pairs of
     * integration_type => url_of_that_integration
     */
    public static $INTEGRATION_TYPES = [
        self::ENVIRONMENT_LIVE => "https://webpay3g.transbank.cl/",
        self::ENVIRONMENT_TEST => "https://webpay3gint.transbank.cl/",
        self::ENVIRONMENT_MOCK => ""

    ];
    /**
     * @var $httpClient HttpClient|null
     */
    public static $httpClient = null;
    private static $apiKey = Options::DEFAULT_API_KEY;
    private static $commerceCode = Options::DEFAULT_COMMERCE_CODE;
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

    public static function configureForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_COMMERCE_CODE);
        self::setIntegrationType(self::ENVIRONMENT_TEST);
    }

    public static function configureMallForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_WEBPAY_PLUS_MALL_COMMERCE_CODE);
        self::setIntegrationType(self::ENVIRONMENT_TEST);
    }

    public static function configureDeferredForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_DEFERRED_COMMERCE_CODE);
        self::setIntegrationType(self::ENVIRONMENT_TEST);
    }

    public static function configureForProduction($commerceCode, $apiKey)
    {
        self::setApiKey($apiKey);
        self::setCommerceCode($commerceCode);
        self::setIntegrationType(self::ENVIRONMENT_LIVE);
    }
}
