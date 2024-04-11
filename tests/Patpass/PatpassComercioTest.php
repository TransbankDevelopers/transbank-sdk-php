<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassComercio;
use Transbank\Patpass\Options;

class PatpassComercioTest extends TestCase
{
    /** @test */
    public function it_configures_for_testing()
    {
        PatpassComercio::configureForTesting();
        $options = PatpassComercio::getOptions();

        $this->assertSame(PatpassComercio::DEFAULT_COMMERCE_CODE, $options->getCommerceCode());
        $this->assertSame(PatpassComercio::DEFAULT_API_KEY, $options->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $options->getIntegrationType());
    }

    /** @test */
    public function it_configures_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        PatpassComercio::configureForIntegration($commerceCode, $apiKey);
        $options = PatpassComercio::getOptions();

        $this->assertSame($commerceCode, $options->getCommerceCode());
        $this->assertSame($apiKey, $options->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $options->getIntegrationType());
    }

    /** @test */
    public function it_configures_for_production()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        PatpassComercio::configureForProduction($commerceCode, $apiKey);
        $options = PatpassComercio::getOptions();

        $this->assertSame($commerceCode, $options->getCommerceCode());
        $this->assertSame($apiKey, $options->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $options->getIntegrationType());
    }
}
