<?php

namespace Transbank\Webpay;

use Transbank\Utils\EnvironmentManager;

class Oneclick extends EnvironmentManager
{
    const DEFAULT_COMMERCE_CODE = '597055555541';
    const DEFAULT_CHILD_COMMERCE_CODE_1 = '597055555542';
    const DEFAULT_CHILD_COMMERCE_CODE_2 = '597055555543';

    const DEFAULT_DEFERRED_COMMERCE_CODE = '597055555547';
    const DEFAULT_DEFERRED_CHILD_COMMERCE_CODE_1 = '597055555548';
    const DEFAULT_DEFERRED_CHILD_COMMERCE_CODE_2 = '597055555549';

    protected static $globalOptions = null;

    public static function configureForTesting()
    {
        self::configureForIntegration(static::DEFAULT_COMMERCE_CODE, static::DEFAULT_API_KEY);
    }

    public static function configureForTestingDeferred()
    {
        self::configureForIntegration(static::DEFAULT_DEFERRED_COMMERCE_CODE, static::DEFAULT_API_KEY);
    }
}
