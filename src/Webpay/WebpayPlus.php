<?php


namespace Transbank\Webpay;

use Transbank\Utils\HttpClient;
use Transbank\Webpay\ConfiguresEnvironment;
use Transbank\Webpay\Modal\WebpayModal;

/**
 * Class WebpayPlus
 *
 * @package Transbank\Webpay
 *
 */
class WebpayPlus
{
    use ConfiguresEnvironment;
    
    /**
     *
     */
    const ENVIRONMENT_LIVE = Options::ENVIRONMENT_LIVE;
    /**
     *
     */
    const ENVIRONMENT_TEST = Options::ENVIRONMENT_TEST;
    /**
     *
     */
    const ENVIRONMENT_MOCK = Options::ENVIRONMENT_MOCK;
    
    /**
     * @var string
     */
    private static $apiKey = Options::DEFAULT_API_KEY;
    /**
     * @var string
     */
    private static $commerceCode = Options::DEFAULT_COMMERCE_CODE;
    /**
     * @var string
     */
    private static $integrationType = Options::DEFAULT_INTEGRATION_TYPE;
    
    /**
     *
     */
    public static function configureForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_COMMERCE_CODE);
        self::setIntegrationType(self::ENVIRONMENT_TEST);
    }
    
    /**
     *
     */
    public static function configureMallForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_WEBPAY_PLUS_MALL_COMMERCE_CODE);
        self::setIntegrationType(self::ENVIRONMENT_TEST);
    }
    
    /**
     *
     */
    public static function configureMallDeferredForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_WEBPAY_PLUS_MALL_DEFERRED_COMMERCE_CODE);
        self::setIntegrationType(self::ENVIRONMENT_TEST);
    }
    
    /**
     *
     */
    public static function configureDeferredForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_DEFERRED_COMMERCE_CODE);
        self::setIntegrationType(self::ENVIRONMENT_TEST);
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
