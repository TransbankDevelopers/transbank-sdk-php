<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\Options;
use Transbank\Patpass\PatpassComercio\Inscription;

class PatpassComercioTest extends TestCase
{
    /** @test */
    public function it_configures_with_options()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $options = new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION);
        $inscription = new Inscription($options);
        $inscriptionOptions = $inscription->getOptions();

        $this->assertSame($commerceCode, $inscriptionOptions->getCommerceCode());
        $this->assertSame($apiKey, $inscriptionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $inscriptionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $inscriptionOptions->getApiBaseUrl());
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
