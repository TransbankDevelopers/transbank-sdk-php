<?php

namespace Transbank\Webpay;

use Transbank\Contracts\EnvironmentManagerContract;
use Transbank\Utils\ConfiguresEnvironment;
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
        self::setApiKey(static::DEFAULT_API_KEY);
        self::setCommerceCode(static::DEFAULT_COMMERCE_CODE);
        self::setIntegrationType(Options::DEFAULT_INTEGRATION_TYPE);
    }

    public static function configureForTestingDeferred()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_ONECLICK_MALL_DEFERRED_COMMERCE_CODE);
        self::setIntegrationType(Options::DEFAULT_INTEGRATION_TYPE);
    }
}
