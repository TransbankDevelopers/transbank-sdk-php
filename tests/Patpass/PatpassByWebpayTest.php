<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassByWebpay;
use Transbank\Webpay\Options;

class PatpassByWebpayTest extends TestCase
{
    /** @test */
    public function it_configures_for_testing()
    {
        PatpassByWebpay::configureForTesting();
        $options = PatpassByWebpay::getDefaultOptions();

        $this->assertSame(PatpassByWebpay::DEFAULT_COMMERCE_CODE, $options->getCommerceCode());
        $this->assertSame(PatpassByWebpay::DEFAULT_API_KEY, $options->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $options->getIntegrationType());
    }

    /** @test */
    public function it_returns_default_options()
    {
        $options = PatpassByWebpay::getDefaultOptions();

        $this->assertSame(PatpassByWebpay::DEFAULT_COMMERCE_CODE, $options->getCommerceCode());
        $this->assertSame(PatpassByWebpay::DEFAULT_API_KEY, $options->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $options->getIntegrationType());
    }
}