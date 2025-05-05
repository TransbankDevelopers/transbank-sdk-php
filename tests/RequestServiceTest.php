<?php

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Utils\HttpClient;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Options;

class RequestServiceTest extends TestCase
{
    #[Test]
    public function it_send_the_headers_provided_by_the_given_options()
    {
        $expectedHeaders = ['api_key' => 'commerce_code', 'api_secret' => 'fakeApiKey'];
        $timeOut = 10;
        $optionsMock = $this->createMock(Options::class);
        $optionsMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn($expectedHeaders);
        $optionsMock
            ->expects($this->once())
            ->method('getTimeout')
            ->willReturn($timeOut);

        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->with($this->anything(), $this->anything(), $this->anything(), $this->equalTo([
                'headers' => $expectedHeaders,
                'timeout' => $timeOut
            ]))
            ->willReturn(
                new Response(200, [], json_encode(['token' => 'mock', 'url'   => 'https://mock.cl/',]))
            );
        (new HttpClientRequestService($httpClientMock))->request('POST', '/transactions', [], $optionsMock);
    }

    #[Test]
    public function it_uses_the_base_url_provided_by_the_given_options()
    {
        $expectedBaseUrl = 'https://mock.mock/';
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
            ->with($this->anything(), $expectedBaseUrl . $endpoint, $this->anything())
            ->willReturn(
                new Response(200, [], json_encode(['token' => 'mock', 'url'   => 'https://mock.cl/',]))
            );
        (new HttpClientRequestService($httpClientMock))->request('POST', $endpoint, [], $optionsMock);
    }

    #[Test]
    public function it_returns_an_empty_array()
    {
        $options = new Options('ApiKey', 'commerceCode', Options::ENVIRONMENT_INTEGRATION);
        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn(new Response(204));
        $response = (new HttpClientRequestService($httpClientMock))->request('DELETE', '/inscriptions', [], $options);
        $this->assertSame([], $response);
    }

    #[Test]
    public function it_returns_an_api_request()
    {
        $options = new Options('ApiKey', 'commerceCode', Options::ENVIRONMENT_INTEGRATION);
        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn(new Response(204));

        $httpClientRequestService = (new HttpClientRequestService($httpClientMock));
        $httpClientRequestService->request('DELETE', '/inscriptions', [], $options);
        $request = $httpClientRequestService->getLastRequest();

        $this->assertSame(Options::BASE_URL_INTEGRATION, $request->getBaseUrl());
        $this->assertSame('/inscriptions', $request->getEndpoint());
        $this->assertSame([
            'Tbk-Api-Key-Id' => 'commerceCode',
            'Tbk-Api-Key-Secret' => 'ApiKey'
        ], $request->getHeaders());
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame([], $request->getPayload());
    }
}
