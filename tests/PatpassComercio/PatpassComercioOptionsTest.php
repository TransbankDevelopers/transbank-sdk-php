<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\PatpassComercio\Options;

class PatpassComercioOptionsTest extends TestCase
{
    #[Test]
    public function it_returns_the_right_headers_based_on_configuration()
    {
        $options = new Options('ApiKey', 'CommerceCode', 'TEST');
        $this->assertSame($options->getHeaders(), [
            'commercecode'  => 'CommerceCode',
            'Authorization' => 'ApiKey',
        ]);
    }
}
