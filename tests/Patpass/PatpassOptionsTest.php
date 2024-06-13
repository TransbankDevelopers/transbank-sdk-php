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
}
