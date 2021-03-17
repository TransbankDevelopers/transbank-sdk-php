<?php

/**
 * Class PatpassComercio.
 *
 * @category
 */

namespace Transbank\Patpass;

use Transbank\Patpass\PatpassComercio\BasePatpassComercio;

class PatpassComercio extends BasePatpassComercio
{
    public static $INTEGRATION_TYPES = [
        Options::ENVIRONMENT_LIVE => 'https://www.pagoautomaticocontarjetas.cl/',
        Options::ENVIRONMENT_TEST => 'https://pagoautomaticocontarjetasint.transbank.cl/',
        Options::ENVIRONMENT_MOCK => '',
    ];

    protected static $apiKey = Options::DEFAULT_PATPASS_COMERCIO_API_KEY;
    protected static $commerceCode = Options::DEFAULT_PATPASS_COMERCIO_COMMERCE_CODE;
    protected static $integrationType = Options::DEFAULT_INTEGRATION_TYPE;

    public static function configureForTesting()
    {
        self::setApiKey(Options::DEFAULT_PATPASS_COMERCIO_API_KEY);
        self::setCommerceCode(Options::DEFAULT_PATPASS_COMERCIO_COMMERCE_CODE);
        self::setIntegrationType('TEST');
    }

    /**
     * Get the default options if none are given.
     *
     * @param Options|null $options
     *
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
