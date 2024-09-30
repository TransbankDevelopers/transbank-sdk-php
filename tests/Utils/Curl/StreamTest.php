<?php

use PHPUnit\Framework\TestCase;
use Transbank\Utils\Curl\Stream;
use Transbank\Utils\Curl\Exceptions\StreamException;

class StreamTest extends TestCase
{
    private Stream $stream;

    public function setUp(): void
    {
        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, 'this is a test data for stream');
        $this->stream = new Stream($resource);
    }

    /** @test */
    public function it_throws_exception_on_constructor_failure()
    {
        $this->expectException(InvalidArgumentException::class);
        new Stream('no resource data');
    }
}
