<?php

namespace Tests\Http;

use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ServerRequestInterface;
use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Credentials\Credentials;
use Transbank\Sdk\Exceptions\ClientException;
use Transbank\Sdk\Exceptions\NetworkException;
use Transbank\Sdk\Exceptions\ServerException;
use Transbank\Sdk\Exceptions\UnknownException;
use Transbank\Sdk\Http\Connector;
use PHPUnit\Framework\TestCase;

class ConnectorTest extends TestCase
{
    /**
     * @var \Mockery\LegacyMockInterface|\Mockery\MockInterface|\Psr\Http\Client\ClientInterface
     */
    protected $client;
    /**
     * @var \Nyholm\Psr7\Factory\Psr17Factory
     */
    protected $requestFactory;
    /**
     * @var \Nyholm\Psr7\Factory\Psr17Factory
     */
    protected $streamFactory;
    /**
     * @var \Transbank\Sdk\Http\Connector
     */
    protected $conector;

    protected function setUp(): void
    {
        $this->client = Mockery::mock(ClientInterface::class);
        $this->requestFactory = $this->streamFactory = new Psr17Factory;

        $this->conector = new Connector($this->client, $this->requestFactory, $this->streamFactory);
    }

    public function test_sends_requests_and_receives_response(): void
    {
        $response = new Response(200, [
            'content-type' => ['application/json'],
        ], '{"foo": "bar"}');

        $this->client->shouldReceive('sendRequest')->withArgs(function(ServerRequestInterface $request) {
            static::assertEquals('qux', $request->getHeader(Connector::HEADER_KEY)[0]);
            static::assertEquals('quuz', $request->getHeader(Connector::HEADER_SECRET)[0]);
            static::assertEquals('/api/' . Connector::API_VERSION . '/example', $request->getUri()->getPath());
            static::assertEquals('https', $request->getUri()->getScheme());
            static::assertEquals('transbank.cl', $request->getUri()->getHost());
            static::assertEquals('foo', $request->getMethod());

            return true;
        })->once()->andReturn($response);

        $array = $this->conector->send(
            'foo',
            'https://transbank.cl/api/{api_version}/example',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );

        static::assertEquals(['foo' => 'bar'], $array);
    }

    public function test_accepts_options_header(): void
    {
        $response = new Response(200, [
            'content-type' => ['application/json'],
        ], '{"foo": "bar"}');

        $this->client->shouldReceive('sendRequest')->withArgs(function(ServerRequestInterface $request) {

            static::assertCount(1, $request->getHeader(Connector::HEADER_KEY));
            static::assertCount(1, $request->getHeader(Connector::HEADER_SECRET));

            static::assertEquals('test_key', $request->getHeader(Connector::HEADER_KEY)[0]);
            static::assertEquals('test_secret', $request->getHeader(Connector::HEADER_SECRET)[0]);

            return true;
        })->once()->andReturn($response);

        $this->conector->send(
            'foo',
            'https://transbank.cl/api/{api_version}/example',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz'),
            [
                'headers' => [
                    Connector::HEADER_KEY => 'test_key',
                    Connector::HEADER_SECRET => 'test_secret',
                ]
            ]
        );
    }

    public function test_sends_request_with_code_204(): void
    {
        $response = new Response(204, [
            'content-type' => ['application/json']
        ], '{"foo": "bar"}');

        $this->client->shouldReceive('sendRequest')->withAnyArgs()->once()->andReturn($response);

        $array = $this->conector->send('foo', 'bar',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );

        static::assertEquals(['foo' => 'bar'], $array);
    }

    public function test_sends_request_and_receives_network_error(): void
    {
        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Could not establish connection with Transbank.');

        $this->client->shouldReceive('sendRequest')->withAnyArgs()->once()->andThrow(
            new ConnectException(
                'Something bad happened', new Request('foo', 'foo/bar'),
            )
        );

        $this->conector->send('foo', 'bar',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );
    }

    public function test_sends_request_and_receives_error_on_redirection(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('A redirection was returned.');

        $response = new Response(303, [
            'content-type' => ['application/json']
        ], '{"foo": "bar"}');

        $this->client->shouldReceive('sendRequest')->withAnyArgs()->once()->andReturn($response);

        $this->conector->send('foo', 'bar',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );
    }

    public function test_sends_request_and_receives_unknown_error(): void
    {
        $this->expectException(UnknownException::class);
        $this->expectExceptionMessage('An error occurred when trying to communicate with Transbank.');

        $this->client->shouldReceive('sendRequest')->withAnyArgs()->once()->andThrow(
            new Exception('Something bad!')
        );

        $this->conector->send('foo', 'bar',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );
    }

    public function test_sends_request_and_receives_client_error(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('This is bad!');

        $response = new Response(404, [
            'content-type' => ['application/json']
        ], '{"error_message": "This is bad!"}');

        $this->client->shouldReceive('sendRequest')->withAnyArgs()->once()->andReturn($response);

        $this->conector->send('foo', 'bar',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );
    }

    public function test_sends_request_and_receives_server_error(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('This is SUPER bad!');

        $response = new Response(500, [
            'content-type' => ['application/json']
        ], '{"error_message": "This is SUPER bad!"}');

        $this->client->shouldReceive('sendRequest')->withAnyArgs()->once()->andReturn($response);

        $this->conector->send('foo', 'bar',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );
    }

    public function test_sends_request_and_error_on_non_json_content(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('Non-JSON response received.');

        $response = new Response(200, [
            'content-type' => ['application/xml']
        ], '{"error_message": "This is not JSON!"}');

        $this->client->shouldReceive('sendRequest')->withAnyArgs()->once()->andReturn($response);

        $this->conector->send('foo', 'bar',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );
    }

    public function test_sends_request_and_error_on_invalid_json(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('The response JSON is malformed.');

        $response = new Response(200, [
            'content-type' => ['application/json']
        ], 'This is not JSON!');

        $this->client->shouldReceive('sendRequest')->withAnyArgs()->once()->andReturn($response);

        $this->conector->send('foo', 'bar',
            new ApiRequest('bar', ['baz' => 'quz']),
            new Credentials('qux', 'quuz')
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
