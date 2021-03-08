<?php


namespace Transbank\Webpay\WebpayPlus;

use Transbank\Utils\HttpClient;
use Transbank\Webpay\ConfiguresEnvironment;
use Transbank\Webpay\Options;

/**
 * Class WebpayPlus
 *
 * @package Transbank\Webpay
 *
 */
class WebpayPlus
{
    use ConfiguresEnvironment;

    const ENVIRONMENT_LIVE = 'LIVE';
    const ENVIRONMENT_TEST = 'TEST';
    const ENVIRONMENT_MOCK = 'MOCK';

    private static $apiKey = Options::DEFAULT_API_KEY;
    private static $commerceCode = Options::DEFAULT_COMMERCE_CODE;
    private static $integrationType = Options::DEFAULT_INTEGRATION_TYPE;

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
}
