<?php

namespace Transbank\Webpay\Modal;

use Transbank\Webpay\ConfiguresEnvironment;
use Transbank\Webpay\Options;

class WebpayModal
{
    use ConfiguresEnvironment;

    protected static $integrationType = Options::ENVIRONMENT_TEST;
    protected static $apiKey = Options::DEFAULT_API_KEY;
    protected static $commerceCode = Options::DEFAULT_WEBPAY_MODAL_COMMERCE_CODE;

    public static function getDefaultOptions()
    {
        return new Options(WebpayModal::getApiKey(), WebpayModal::getCommerceCode(), WebpayModal::getIntegrationType());
    }
}
