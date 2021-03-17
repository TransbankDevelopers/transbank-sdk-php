<?php

namespace Transbank\Webpay\Oneclick\Traits;

use Transbank\Webpay\Oneclick;

trait NeedsConfiguration
{
    public static function getCommerceIdentifier($options)
    {
        if ($options == null) {
            $commerceCode = Oneclick::getCommerceCode();
            $apiKey = Oneclick::getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl($options->getIntegrationType());
        }

        return [
            $commerceCode,
            $apiKey,
            $baseUrl,
        ];
    }
}
