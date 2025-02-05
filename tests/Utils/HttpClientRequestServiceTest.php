<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Contracts\HttpClientInterface;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Transbank\Utils\TransbankApiRequest;

class HttpClientRequestServiceTest extends TestCase
{
    public function test_request_success()
    {
        $expectedHeaders = ['api_key' => 'commerce_code', 'api_secret' => 'fakeApiKey'];
        $expectedResponse = ['token' => 'mock', 'url'   => 'https://mock.cl/'];
        $timeOut = 10;

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $streamMock = $this->createMock(StreamInterface::class);
        $optionsMock = $this->createMock(Options::class);

        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getBody')->willReturn($streamMock);

        $streamMock->method('__toString')->willReturn(json_encode($expectedResponse));

        $optionsMock->method('getHeaders')->willReturn($expectedHeaders);
        $optionsMock->method('getApiBaseUrl')->willReturn('https://api.transbank.cl/');
        $optionsMock->method('getTimeout')->willReturn($timeOut);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->with('POST', 'https://api.transbank.cl/endpoint', ['data' => 'value'], [
                'headers' => $expectedHeaders,
                'timeout' => $timeOut
            ])
            ->willReturn($responseMock);

        $service = new HttpClientRequestService($httpClientMock);
        $result = $service->request('POST', 'endpoint', ['data' => 'value'], $optionsMock);

        $this->assertEquals($expectedResponse, $result);
        $this->assertInstanceOf(ResponseInterface::class, $service->getLastResponse());
        $this->assertInstanceOf(TransbankApiRequest::class, $service->getLastRequest());
        $this->assertEquals($httpClientMock, $service->getHttpClient());
    }

    public function test_request_throws_webpay_request_exception_on_error()
    {
        $expectedHeaders = ['api_key' => 'commerce_code', 'api_secret' => 'fakeApiKey'];
        $expectedResponse = ['error_message' => 'Internal Server Error'];

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $streamMock = $this->createMock(StreamInterface::class);
        $optionsMock = $this->createMock(Options::class);

        $responseMock->method('getStatusCode')->willReturn(500);
        $responseMock->method('getBody')->willReturn($streamMock);

        $streamMock->method('__toString')->willReturn(json_encode($expectedResponse));

        $responseMock->method('getReasonPhrase')->willReturn('Internal Server Error');
        $optionsMock->method('getHeaders')->willReturn($expectedHeaders);
        $optionsMock->method('getApiBaseUrl')->willReturn('https://api.transbank.cl/');
        $optionsMock->method('getTimeout')->willReturn(30);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);

        $service = new HttpClientRequestService($httpClientMock);

        $this->expectException(WebpayRequestException::class);
        $service->request('POST', 'endpoint', ['data' => 'value'], $optionsMock);
    }

    public function test_set_and_get_last_response()
    {
        $service = new HttpClientRequestService();
        $responseMock = $this->createMock(ResponseInterface::class);

        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('setLastResponse');
        $method->setAccessible(true);
        $method->invoke($service, $responseMock);

        $this->assertSame($responseMock, $service->getLastResponse());
    }

    public function test_set_and_get_last_request()
    {
        $service = new HttpClientRequestService();
        $requestMock = $this->createMock(TransbankApiRequest::class);

        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('setLastRequest');
        $method->setAccessible(true);
        $method->invoke($service, $requestMock);

        $this->assertSame($requestMock, $service->getLastRequest());
    }
}
