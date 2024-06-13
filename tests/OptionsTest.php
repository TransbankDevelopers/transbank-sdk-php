<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Options;

class OptionsTest extends TestCase
{
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
}
