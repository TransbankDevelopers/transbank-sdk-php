<?php

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Transbank\Utils\HttpClient;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Options;

class RequestServiceTest extends TestCase
{
    /** @test */
    public function it_send_the_headers_provided_by_the_given_options()
    {
        $expectedHeaders = ['api_key' => 'commerce_code', 'api_secret' => 'fakeApiKey'];

        $optionsMock = $this->createMock(Options::class);
        $optionsMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn($expectedHeaders);

        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with($this->anything(), $this->anything(), $this->anything(), $this->equalTo([
                'headers' => $expectedHeaders,
            ]))
            ->willReturn(
                new Response(200, [], json_encode([
                    'token' => 'mock',
                    'url'   => 'http://mock.cl/',
                ]))
            );

        $request = (new HttpClientRequestService($httpClientMock))->request('POST', '/transactions', [], $optionsMock);
    }

    /** @test */
    public function it_uses_the_base_url_provided_by_the_given_options()
    {
        $expectedBaseUrl = 'http://mock.mock/';
        $endpoint = '/transactions';

        $optionsMock = $this->createMock(Options::class);
        $optionsMock
            ->expects($this->once())
            ->method('getApiBaseUrl')
            ->willReturn($expectedBaseUrl);

        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with($this->anything(), $expectedBaseUrl.$endpoint, $this->anything())
            ->willReturn(
                new Response(200, [], json_encode([
                    'token' => 'mock',
                    'url'   => 'http://mock.cl/',
                ]))
            );

        $request = (new HttpClientRequestService($httpClientMock))
            ->request('POST', $endpoint, [], $optionsMock);
    }
}
