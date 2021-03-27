<?php

namespace Tests\Exceptions;

use Exception;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Exceptions\ClientException;
use Transbank\Sdk\Exceptions\NetworkException;
use Transbank\Sdk\Exceptions\ServerException;
use Transbank\Sdk\Exceptions\TransbankException;
use Transbank\Sdk\Exceptions\UnknownException;

class ClientExceptionTest extends TestCase
{
    public function test_client_exception_has_api_request_message_and_response(): void
    {
        $exception = new ClientException(
            'foo',
            $apiRequest = new ApiRequest('foo', ['foo' => 'bar']),
            $serverRequest = new ServerRequest('foo', 'foo/bar'),
            $response = new Response(200, [], 'foo-bar'),
            $previous = new Exception('previous')
        );

        static::assertEquals($apiRequest, $exception->getApiRequest());
        static::assertEquals($serverRequest, $exception->getServerRequest());
        static::assertEquals($response, $exception->getResponse());
        static::assertEquals($previous, $exception->getPrevious());

        static::assertInstanceOf(TransbankException::class, $exception);
    }

    public function test_network_exception_has_api_request_message_and_response(): void
    {
        $exception = new NetworkException(
            'foo',
            $apiRequest = new ApiRequest('foo', ['foo' => 'bar']),
            $serverRequest = new ServerRequest('foo', 'foo/bar'),
            $response = new Response(200, [], 'foo-bar'),
            $previous = new Exception('previous')
        );

        static::assertEquals($apiRequest, $exception->getApiRequest());
        static::assertEquals($serverRequest, $exception->getServerRequest());
        static::assertEquals($response, $exception->getResponse());
        static::assertEquals($previous, $exception->getPrevious());

        static::assertInstanceOf(TransbankException::class, $exception);
    }

    public function test_server_exception_has_api_request_message_and_response(): void
    {
        $exception = new ServerException(
            'foo',
            $apiRequest = new ApiRequest('foo', ['foo' => 'bar']),
            $serverRequest = new ServerRequest('foo', 'foo/bar'),
            $response = new Response(200, [], 'foo-bar'),
            $previous = new Exception('previous')
        );

        static::assertEquals($apiRequest, $exception->getApiRequest());
        static::assertEquals($serverRequest, $exception->getServerRequest());
        static::assertEquals($response, $exception->getResponse());
        static::assertEquals($previous, $exception->getPrevious());

        static::assertInstanceOf(TransbankException::class, $exception);
    }

    public function test_unknown_exception_has_api_request_message_and_response(): void
    {
        $exception = new UnknownException(
            'foo',
            $apiRequest = new ApiRequest('foo', ['foo' => 'bar']),
            $serverRequest = new ServerRequest('foo', 'foo/bar'),
            $response = new Response(200, [], 'foo-bar'),
            $previous = new Exception('previous')
        );

        static::assertEquals($apiRequest, $exception->getApiRequest());
        static::assertEquals($serverRequest, $exception->getServerRequest());
        static::assertEquals($response, $exception->getResponse());
        static::assertEquals($previous, $exception->getPrevious());

        static::assertInstanceOf(TransbankException::class, $exception);
    }
}
