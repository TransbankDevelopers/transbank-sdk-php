<?php

namespace Transbank\Contracts;

use Transbank\Webpay\Options;

interface EnvironmentManager
{
    public static function getIntegrationTypeUrl($integrationType = null);
    public static function configureForTesting();
    public static function configureForProduction($commerceCode, $apiKey);
    public function getDefaultOptions();
}
