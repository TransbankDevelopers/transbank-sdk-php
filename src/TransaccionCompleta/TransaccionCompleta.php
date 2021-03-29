<?php

/**
 * Class TransaccionCompleta.
 *
 * @category
 */

namespace Transbank\TransaccionCompleta;

use Transbank\Utils\ConfiguresEnvironment;
use Transbank\Utils\EnvironmentManager;
use Transbank\Webpay\Options;

class TransaccionCompleta extends EnvironmentManager
{
    const DEFAULT_COMMERCE_CODE = '597055555532';
    
    const DEFAULT_MALL_COMMERCE_CODE = '597055555573';
    const DEFAULT_MALL_CHILD_COMMERCE_CODE_1 = '597055555574';
    const DEFAULT_MALL_CHILD_COMMERCE_CODE_2 = '597055555575';
    
    public static function configureForTesting()
    {
        static::configureForIntegration(static::DEFAULT_COMMERCE_CODE);
    }

    public static function configureMallForTesting()
    {
        static::configureForIntegration(static::DEFAULT_MALL_COMMERCE_CODE);
    }
}
