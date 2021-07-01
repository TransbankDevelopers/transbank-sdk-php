<?php

namespace Transbank\Utils;

use Transbank\Webpay\Options;

/**
 * Trait ConfiguresEnvironment.
 */
trait ConfiguresEnvironment
{
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

    /**
     * @param Options|null $options
     */
    public static function setOptions(Options $options = null)
    {
        static::$globalOptions = $options;
    }

    /**
     * @return Options|null
     */
    public static function getOptions()
    {
        return static::$globalOptions;
    }

    public static function reset()
    {
        static::setOptions(null);
    }
}
