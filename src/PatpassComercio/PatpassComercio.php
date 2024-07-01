<?php

/**
 * Class PatpassComercio.
 *
 * @category
 */

 namespace Transbank\PatpassComercio;


class PatpassComercio
{
    public const INTEGRATION_API_KEY = 'cxxXQgGD9vrVe4M41FIt';
    public const INTEGRATION_COMMERCE_CODE = '28299257';

    /**
     * @var string
     */
    protected string $integrationApiKey;

    /**
     * @var string
     */
    protected string $integrationCommerceCode;

    public function __construct()
    {
        $this->integrationApiKey = self::INTEGRATION_API_KEY;
        $this->integrationCommerceCode = self::INTEGRATION_COMMERCE_CODE;
    }

    /**
     * @return string
     */
    public function getIntegrationApiKey(): string
    {
        return $this->integrationApiKey;
    }

    /**
     * @return string
     */
    public function getIntegrationCommerceCode(): string
    {
        return $this->integrationCommerceCode;
    }
}
