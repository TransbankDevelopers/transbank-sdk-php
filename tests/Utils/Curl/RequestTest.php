<?php

use PHPUnit\Framework\TestCase;
use Transbank\Utils\Curl\Request;
use Transbank\Utils\Curl\Uri;
use Transbank\Utils\Curl\Stream;


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
}
