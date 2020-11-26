<?php


namespace Transbank\Patpass;

use Transbank\Utils\HttpClient;

class PatpassByWebpay
{
    private static $httpClient;
    private static $commerceCode;
    private static $apiKey;
    /**
     * @var array $INTEGRATION_TYPES contains key-value pairs of
     * integration_type => url_of_that_integration
     */
    public static $INTEGRATION_TYPES = [
        "LIVE" => "https://webpay3g.transbank.cl/",
        "TEST" => "https://webpay3gint.transbank.cl/",
        "MOCK" => ""
    ];

    private static $integrationType = Options::DEFAULT_INTEGRATION_TYPE;

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
     * @return mixed
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    public static function configureForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_PATPASS_BY_WEBPAY_COMMERCE_CODE);
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
}
