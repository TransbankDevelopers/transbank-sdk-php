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

    /** @test */
    public function it_gets_empty_string_on_failure()
    {
        $mockStream = $this->getMockBuilder(Stream::class)
            ->setConstructorArgs([fopen('php://temp', 'r')])
            ->onlyMethods(['isSeekable'])
            ->getMock();
        $mockStream->method('isSeekable')->willThrowException(new Exception('test Exception'));
        $result = (string) $mockStream;
        $this->assertEquals('', $result);
    }

    /** @test */
    public function it_can_close_resource()
    {
        $this->stream->close();
        $this->expectException(StreamException::class);
        $this->stream->getContents();
    }

    /** @test */
    public function it_can_detach_resource()
    {
        $resource = $this->stream->detach();
        $this->assertIsResource($resource);
        $this->expectException(StreamException::class);
        $this->stream->getContents();
    }

    /** @test */
    public function it_can_get_size()
    {
        $this->assertGreaterThan(0, $this->stream->getSize());
        $this->stream->close();
        $this->assertNull($this->stream->getSize());
    }

    /** @test */
    public function it_can_tell_position()
    {
        $this->assertEquals(30, $this->stream->tell());
        $this->stream->close();

        $this->expectException(StreamException::class);
        $this->stream->tell();
    }

    /** @test */
    public function it_can_get_eof()
    {
        $this->assertFalse($this->stream->eof());
    }
}
