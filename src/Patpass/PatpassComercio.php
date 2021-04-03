<?php

/**
 * Class PatpassComercio.
 *
 * @category
 */

namespace Transbank\Patpass;

use Transbank\Utils\EnvironmentManager;

class PatpassComercio extends EnvironmentManager
{
    const DEFAULT_API_KEY = 'cxxXQgGD9vrVe4M41FIt';
    const DEFAULT_COMMERCE_CODE = '28299257';

    protected static $globalOptions = null;

    public static function configureForTesting()
    {
        self::configureForIntegration(static::DEFAULT_COMMERCE_CODE, static::DEFAULT_API_KEY);
    }

    /**
     * @param $commerceCode
     * @param string $apiKey
     */
    public static function configureForIntegration($commerceCode, $apiKey = Options::DEFAULT_API_KEY)
    {
        static::setOptions(Options::forIntegration($commerceCode, $apiKey));
    }

    /**
     * @param $commerceCode
     * @param $apiKey
     */
    public static function configureForProduction($commerceCode, $apiKey)
    {
        static::setOptions(Options::forProduction($commerceCode, $apiKey));
    }
}
