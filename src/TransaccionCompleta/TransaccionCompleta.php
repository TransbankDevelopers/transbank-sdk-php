<?php

/**
 * Class TransaccionCompleta.
 *
 * @category
 */

namespace Transbank\TransaccionCompleta;

use Transbank\Utils\EnvironmentManager;

class TransaccionCompleta extends EnvironmentManager
{
    const DEFAULT_COMMERCE_CODE = '597055555530';
    const DEFAULT_DEFERRED_COMMERCE_CODE = '597055555531';
    const DEFAULT_NO_CVV_COMMERCE_CODE = '597055555557';
    const DEFAULT_DEFERRED_NO_CVV_COMMERCE_CODE = '597055555556';

    const DEFAULT_MALL_COMMERCE_CODE = '597055555573';
    const DEFAULT_MALL_CHILD_COMMERCE_CODE_1 = '597055555574';
    const DEFAULT_MALL_CHILD_COMMERCE_CODE_2 = '597055555575';

    const DEFAULT_MALL_NO_CVV_COMMERCE_CODE = '597055555551';
    const DEFAULT_MALL_NO_CVV_CHILD_COMMERCE_CODE_1 = '597055555552';
    const DEFAULT_MALL_NO_CVV_CHILD_COMMERCE_CODE_2 = '597055555553';

    const DEFAULT_MALL_DEFERRED_COMMERCE_CODE = '597055555576';
    const DEFAULT_MALL_DEFERRED_CHILD_COMMERCE_CODE_1 = '597055555577';
    const DEFAULT_MALL_DEFERRED_CHILD_COMMERCE_CODE_2 = '597055555578';

    const DEFAULT_MALL_DEFERRED_NO_CVV_COMMERCE_CODE = '597055555561';
    const DEFAULT_MALL_DEFERRED_NO_CVV_CHILD_COMMERCE_CODE_1 = '597055555562';
    const DEFAULT_MALL_DEFERRED_NO_CVV_CHILD_COMMERCE_CODE_2 = '597055555564';

    protected static $globalOptions = null;

    public static function configureForTesting()
    {
        static::configureForIntegration(static::DEFAULT_COMMERCE_CODE);
    }

    public static function configureForTestingNoCVV()
    {
        static::configureForIntegration(static::DEFAULT_NO_CVV_COMMERCE_CODE);
    }

    public static function configureForTestingDeferred()
    {
        static::configureForIntegration(static::DEFAULT_DEFERRED_COMMERCE_CODE);
    }

    public static function configureForTestingDeferredNoCVV()
    {
        static::configureForIntegration(static::DEFAULT_DEFERRED_NO_CVV_COMMERCE_CODE);
    }

    public static function configureForTestingMall()
    {
        static::configureForIntegration(static::DEFAULT_MALL_COMMERCE_CODE);
    }

    public static function configureForTestingMallNoCVV()
    {
        static::configureForIntegration(static::DEFAULT_MALL_NO_CVV_COMMERCE_CODE);
    }

    public static function configureForTestingMallDeferred()
    {
        static::configureForIntegration(static::DEFAULT_MALL_DEFERRED_COMMERCE_CODE);
    }

    public static function configureForTestingMallDeferredNoCVV()
    {
        static::configureForIntegration(static::DEFAULT_MALL_DEFERRED_NO_CVV_COMMERCE_CODE);
    }
}
