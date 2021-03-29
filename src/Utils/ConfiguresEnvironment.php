<?php

namespace Transbank\Utils;

use Transbank\Webpay\Options;

trait ConfiguresEnvironment
{
    /**
     * @return string
     */
    public static function getIntegrationType()
    {
        return static::$integrationType;
    }

    public static function configureForIntegration($commerceCode, $apiKey = Options::DEFAULT_API_KEY)
    {
        static::setOptions(Options::forIntegration($commerceCode, $apiKey));
    }

    public static function configureForProduction($commerceCode, $apiKey)
    {
        static::setOptions(Options::forProduction($commerceCode, $apiKey));
    }

    public static function setOptions(Options $options = null)
    {
        static::$globalOptions = $options;
    }

    public static function getOptions()
    {
        return static::$globalOptions;
    }

    public static function reset()
    {
        static::setOptions(null);
    }
}
