<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\Options;

class PatpassOptionsTest extends TestCase
{
    /** @test */
    public function it_returns_the_right_headers_based_on_configuration()
    {
        $options = new Options('ApiKey', 'CommerceCode', 'TEST');
        $this->assertSame($options->getHeaders(), [
            'commercecode'  => 'CommerceCode',
            'Authorization' => 'ApiKey',
        ]);
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
