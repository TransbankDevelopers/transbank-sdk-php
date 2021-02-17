<?php

namespace Transbank\Webpay;

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
        self::setIntegrationType('LIVE');
    }
}
