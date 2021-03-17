<?php


namespace Transbank\Webpay;

use Transbank\Utils\HttpClient;

class Oneclick
{
    use ConfiguresEnvironment;

    private static $apiKey = Options::DEFAULT_API_KEY;
    private static $commerceCode = Options::DEFAULT_ONECLICK_MALL_COMMERCE_CODE;
    private static $integrationType = Options::DEFAULT_INTEGRATION_TYPE;


    public static function configureOneclickMallForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_ONECLICK_MALL_COMMERCE_CODE);
        self::setIntegrationType(Options::DEFAULT_INTEGRATION_TYPE);
    }

    public static function configureOneclickMallDeferredForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_ONECLICK_MALL_DEFERRED_COMMERCE_CODE);
        self::setIntegrationType(Options::DEFAULT_INTEGRATION_TYPE);
    }

    public static function configureOneclickMallForProduction($commerceCode, $apiKey)
    {
        self::setApiKey($apiKey);
        self::setCommerceCode($commerceCode);
        self::setIntegrationType("LIVE");
    }
    
    /**
     * Get the default options if none are given.
     *
     * @param Options|null $options
     * @return Options
     */
    public static function getDefaultOptions(Options $options = null)
    {
        if ($options !== null) {
            return $options;
        }
        return new Options(static::getApiKey(), static::getCommerceCode(), static::getIntegrationType());
    }
}
