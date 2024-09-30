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

    /** @test */
    public function it_can_set_class_properties(): void
    {
        $newResponse = $this->response->withStatus(404, 'NotFound');
        $this->assertEquals(404, $newResponse->getStatusCode());
        $this->assertEquals('NotFound', $newResponse->getReasonPhrase());
        $this->assertNotSame($this->response, $newResponse);

        $newResponse = $this->response->withProtocolVersion('1.0');
        $this->assertEquals('1.0', $newResponse->getProtocolVersion());
        $this->assertNotSame($this->response, $newResponse);

        $newResponse = $this->response->withHeader('testHeader', 'testValue');
        $this->assertNotSame($newResponse, $this->response);

        $newResponse = $this->response->withAddedHeader('test', 'testValue');
        $this->assertNotSame($newResponse, $this->response);

        $newResponse = $this->response->withoutHeader('Accept');
        $this->assertEquals([], $newResponse->getHeader('Accept'));
        $this->assertNotSame($newResponse, $this->response);


        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, 'testData');
        $newResponse = $this->response->withBody(new Stream($resource));
        $this->assertFalse($newResponse->getBody() == $this->response->getBody());
        $this->assertNotSame($newResponse, $this->response);
    }
}
