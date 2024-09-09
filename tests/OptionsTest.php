<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Options;

class OptionsTest extends TestCase
{
    private Options $options;
    public function setUp(): void
    {
        $apiKey = 'testApiKey';
        $commerceCode = 'testCommerceCode';
        $this->options = new Options($apiKey, $commerceCode, Options::ENVIRONMENT_INTEGRATION);
    }

    /** @test */
    public function it_assign_contructor_params_to_their_corresponding_properties()
    {
        $options = new Options('a', 'b', 'c', 10);
        $this->assertSame('a', $options->getApiKey());
        $this->assertSame('b', $options->getCommerceCode());
        $this->assertSame('c', $options->getIntegrationType());
        $this->assertSame(10, $options->getTimeout());
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

    /** @test */
    public function it_set_properties()
    {
        $this->options->setIntegrationType(Options::ENVIRONMENT_PRODUCTION);
        $this->options->setApiKey('newApiKey');
        $this->options->setCommerceCode('newCommerceCode');
        $this->options->setTimeout(100);

        $this->assertEquals(Options::ENVIRONMENT_PRODUCTION, $this->options->integrationType);
        $this->assertEquals('newApiKey', $this->options->apiKey);
        $this->assertEquals('newCommerceCode', $this->options->commerceCode);
        $this->assertEquals(100, $this->options->getTimeout());
    }

    /** @test */
    public function it_check_if_is_production()
    {
        $this->options->setIntegrationType(Options::ENVIRONMENT_PRODUCTION);
        $this->assertEquals(true, $this->options->isProduction());
    }

}
