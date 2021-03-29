<?php

namespace Transbank\Webpay\Modal;

use Transbank\Utils\ConfiguresEnvironment;
use Transbank\Utils\EnvironmentManager;
use Transbank\Webpay\Options;

class WebpayModal extends EnvironmentManager
{
    const DEFAULT_COMMERCE_CODE = '597055555584';
    const DEFAULT_API_KEY = Options::DEFAULT_API_KEY;
    
    protected static $apiKey = self::DEFAULT_API_KEY;
    protected static $commerceCode = self::DEFAULT_COMMERCE_CODE;
    protected static $integrationType = Options::ENVIRONMENT_INTEGRATION;
    
    public static function configureForTesting()
    {
        static::configureForIntegration(static::DEFAULT_COMMERCE_CODE, static::DEFAULT_API_KEY);
    }
}
