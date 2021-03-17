<?php

/**
 * Class TransaccionCompleta
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;

use Transbank\Utils\HttpClient;
use Transbank\TransaccionCompleta\Options;
use Transbank\Webpay\ConfiguresEnvironment;

class TransaccionCompleta
{
    use ConfiguresEnvironment;

    private static $apiKey = Options::DEFAULT_API_KEY;
    private static $commerceCode = Options::DEFAULT_TRANSACCION_COMPLETA_COMMERCE_CODE;
    private static $integrationType = Options::DEFAULT_INTEGRATION_TYPE;

    public static function configureForTesting()
    {
        self::$apiKey = Options::DEFAULT_API_KEY;
        self::$commerceCode = Options::DEFAULT_TRANSACCION_COMPLETA_COMMERCE_CODE;
        self::$integrationType = Options::DEFAULT_INTEGRATION_TYPE;
    }

    public static function configureMallForTesting()
    {
        self::$apiKey = Options::DEFAULT_API_KEY;
        self::$commerceCode = Options::DEFAULT_TRANSACCION_COMPLETA_MALL_COMMERCE_CODE;
        self::$integrationType = Options::DEFAULT_INTEGRATION_TYPE;
    }
    
    /**
     * Get the default options if none are given.
     *
     * @param \Transbank\TransaccionCompleta\Options|null $options
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
