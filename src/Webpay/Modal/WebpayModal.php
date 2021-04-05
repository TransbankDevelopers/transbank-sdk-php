<?php

namespace Transbank\Webpay\Modal;

use Transbank\Utils\EnvironmentManager;
use Transbank\Webpay\Options;

class WebpayModal extends EnvironmentManager
{
    const DEFAULT_COMMERCE_CODE = '597055555584';
    const DEFAULT_API_KEY = Options::DEFAULT_API_KEY;

    protected static $globalOptions = null;

    public static function configureForTesting()
    {
        static::configureForIntegration(static::DEFAULT_COMMERCE_CODE, static::DEFAULT_API_KEY);
    }
}
