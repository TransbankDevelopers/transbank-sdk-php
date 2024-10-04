<?php

use PHPUnit\Framework\TestCase;
use Transbank\Utils\Curl\Request;
use Transbank\Utils\Curl\Uri;


class RequestTest extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        $this->request = new Request('GET', 'https://www.transbank.cl:443/webpay/1.2/transactions/token?param1=123&param2=222', [
            'Accept' => 'text/plain',
            'api_key' => 'fakeApiKey'
        ], null, '1.2');
    }

    /** @test */
    public function it_can_get_class_properties(): void
    {
        $this->assertEquals('/webpay/1.2/transactions/token?param1=123&param2=222', $this->request->getRequestTarget());
        $this->assertEquals('1.2', $this->request->getProtocolVersion());
        $this->assertEquals('text/plain', $this->request->getHeader('Accept')[0]);
        $this->assertEquals('text/plain', $this->request->getHeaderLine('Accept'));
        $this->assertTrue($this->request->hasHeader('Accept'));
    }

    /** @test */
    public function it_can_set_class_properties(): void
    {
        $newRequest = $this->request->withRequestTarget('/api/test/');
        $this->assertEquals('/webpay/1.2/transactions/token?param1=123&param2=222', $this->request->getRequestTarget());
        $this->assertEquals('/api/test/', $newRequest->getRequestTarget());
        $this->assertNotSame($this->request, $newRequest);

        $newRequest = $this->request->withMethod('PUT');
        $this->assertEquals('GET', $this->request->getMethod());
        $this->assertEquals('PUT', $newRequest->getMethod());
        $this->assertNotSame($this->request, $newRequest);

        $uri = new Uri('https://www.transbank.cl:443/');
        $newRequest = $this->request->withUri($uri);
        $this->assertEquals($uri, $newRequest->getUri());
        $this->assertNotSame($newRequest, $this->request);

        $newRequest = $this->request->withProtocolVersion('1.1');
        $this->assertEquals('1.1', $newRequest->getProtocolVersion());
        $this->assertNotSame($newRequest, $this->request);

        $newRequest = $this->request->withHeader('testHeader', 'testValue');
        $this->assertNotSame($newRequest, $this->request);

        $newRequest = $this->request->withAddedHeader('test', 'testValue');
        $this->assertNotSame($newRequest, $this->request);

        $newRequest = $this->request->withoutHeader('Accept');
        $this->assertEquals([], $newRequest->getHeader('Accept'));
        $this->assertNotSame($newRequest, $this->request);

        $secondRequest =
            new Request('GET', 'https://www.transbank.cl:443/webpay/1.2/transactions/token?param1=123&param2=222', [
                'Accept' => 'text/plain',
                'api_key' => 'fakeApiKey'
            ], 'this is a new body', '1.2');
        $newRequest = $this->request->withBody($secondRequest->getBody());
        $this->assertFalse($newRequest->getBody() == $this->request->getBody());
        $this->assertNotSame($newRequest, $this->request);
    }
}
