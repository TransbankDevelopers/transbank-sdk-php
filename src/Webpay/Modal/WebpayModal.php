<?php

namespace Transbank\Webpay\Modal;

use Transbank\Utils\ConfiguresEnvironment;
use Transbank\Webpay\Options;

class WebpayModal
{
    use ConfiguresEnvironment;

    protected static $integrationType = Options::ENVIRONMENT_INTEGRATION;
    protected static $apiKey = Options::DEFAULT_API_KEY;
    protected static $commerceCode = Options::DEFAULT_WEBPAY_MODAL_COMMERCE_CODE;

    public static function configureForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_WEBPAY_MODAL_COMMERCE_CODE);
        self::setIntegrationType(Options::ENVIRONMENT_INTEGRATION);
    }

    public static function getDefaultOptions($options)
    {
        if ($options !== null) {
            return $options;
        }

        return new Options(WebpayModal::getApiKey(), WebpayModal::getCommerceCode(), WebpayModal::getIntegrationType());
    }
}
