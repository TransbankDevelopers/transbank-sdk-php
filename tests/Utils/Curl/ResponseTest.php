<?php

use PHPUnit\Framework\TestCase;
use Transbank\Utils\Curl\Response;
use Transbank\Utils\Curl\Stream;


class ResponseTest extends TestCase
{
    private Response $response;

    public function setUp(): void
    {
        $this->response = new Response(200, [
            'Accept' => 'text/plain',
            'api_key' => 'fakeApiKey'
        ], null);
    }

    /** @test */
    public function it_can_get_class_properties(): void
    {
        $this->assertEquals('1.1', $this->response->getProtocolVersion());
        $this->assertEquals([
            'Accept' => 'text/plain',
            'api_key' => 'fakeApiKey'
        ], $this->response->getHeaders());
        $this->assertEquals('text/plain', $this->response->getHeader('Accept')[0]);
        $this->assertEquals('text/plain', $this->response->getHeaderLine('Accept'));
        $this->assertTrue($this->response->hasHeader('Accept'));
    }
}
