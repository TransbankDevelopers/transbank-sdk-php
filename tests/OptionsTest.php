<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Options;

class OptionsTest extends TestCase
{
    /** @test */
    public function it_assign_contructor_params_to_their_corresponding_properties()
    {
        $options = new Options('a', 'b', 'c');
        $this->assertSame($options->getApiKey(), 'a');
        $this->assertSame($options->getCommerceCode(), 'b');
        $this->assertSame($options->getIntegrationType(), 'c');
    }

    /** @test */
    public function it_returns_the_right_headers_based_on_configuration()
    {
        $options = new Options('ApiKey', 'CommerceCode', 'TEST');
        $this->assertSame($options->getHeaders(), [
            'Tbk-Api-Key-Id'     => 'CommerceCode',
            'Tbk-Api-Key-Secret' => 'ApiKey',
        ]);
    }

    public function it_creates_an_option_using_static_factory_method_for_integration_credentials()
    {
        $options = Options::forIntegration('commerceCode', 'ApiKey');
        $this->assertSame($options->getCommerceCode(), 'commerceCode');
        $this->assertSame($options->getApiKey(), 'ApiKey');
        $this->assertSame($options->getIntegrationType(), Options::ENVIRONMENT_INTEGRATION);
    }

    public function it_creates_an_option_using_static_factory_method_for_production_credentials()
    {
        $options = Options::forProduction('commerceCode', 'ApiKey');
        $this->assertSame($options->getCommerceCode(), 'commerceCode');
        $this->assertSame($options->getApiKey(), 'ApiKey');
        $this->assertSame($options->getIntegrationType(), Options::ENVIRONMENT_PRODUCTION);
    }

    /** @test */
    public function it_check_if_the_current_object_represent_production_credentials()
    {
        $options = Options::forProduction('CommerceCode', 'ApiKey');
        $this->assertTrue($options->isProduction());
    }

    /** @test */
    public function it_return_the_correct_base_url_for_production_credentials()
    {
        $options = Options::forProduction('CommerceCode', 'ApiKey');
        $this->assertSame(Options::BASE_URL_PRODUCTION, $options->getApiBaseUrl());
    }

    /** @test */
    public function it_return_the_correct_base_url_for_integration_credentials()
    {
        $options = Options::forIntegration('CommerceCode', 'ApiKey');
        $this->assertSame(Options::BASE_URL_INTEGRATION, $options->getApiBaseUrl());
    }
}
