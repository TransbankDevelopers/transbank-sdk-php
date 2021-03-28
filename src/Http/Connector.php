<?php

namespace Transbank\Sdk\Http;

use JsonException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamFactoryInterface;
use Throwable;
use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Credentials\Credentials;
use Transbank\Sdk\Exceptions\ClientException;
use Transbank\Sdk\Exceptions\NetworkException;
use Transbank\Sdk\Exceptions\ServerException;
use Transbank\Sdk\Exceptions\UnknownException;
use Transbank\Sdk\Transbank;

class Connector
{
    /**
     * Current API Version to use on Transbank Servers.
     *
     * @var string
     */
    public const API_VERSION = 'v1.2';

    /**
     * Transbank API Key header name.
     *
     * @var string
     */
    public const HEADER_KEY = 'Tbk-Api-Key-Id';

    /**
     * Transbank API Shared Secret header name.
     *
     * @var string
     */
    public const HEADER_SECRET = 'Tbk-Api-Key-Secret';

    /**
     * Production endpoint server.
     *
     * @var string
     */
    public const PRODUCTION_ENDPOINT = 'https://webpay3g.transbank.cl/';

    /**
     * Integration endpoint server.
     *
     * @var string
     */
    public const INTEGRATION_ENDPOINT = 'https://webpay3gint.transbank.cl/';

    /**
     * Connector constructor.
     *
     * @param  \Psr\Http\Client\ClientInterface  $client
     * @param  \Psr\Http\Message\ServerRequestFactoryInterface  $requestFactory
     * @param  \Psr\Http\Message\StreamFactoryInterface  $streamFactory
     */
    public function __construct(
        protected ClientInterface $client,
        protected ServerRequestFactoryInterface $requestFactory,
        protected StreamFactoryInterface $streamFactory
    ) {
    }

    /**
     * Sends an transaction to Transbank servers.
     *
     * @param  string  $method
     * @param  string  $endpoint
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     * @param  \Transbank\Sdk\Credentials\Credentials  $credentials
     * @param  array  $options
     *
     * @return array
     * @throws \JsonException|\Transbank\Sdk\Exceptions\TransbankException
     */
    public function send(
        string $method,
        string $endpoint,
        ApiRequest $apiRequest,
        Credentials $credentials,
        array $options = []
    ): array {
        $request = $this->requestFactory->createServerRequest($method, $this->setApiVersion($endpoint));

        return $this->sendRequest($request, $apiRequest, $credentials, $options);
    }

    /**
     * Replace the API Version from the endpoint.
     *
     * @param  string  $endpoint
     *
     * @return string
     */
    protected function setApiVersion(string $endpoint): string
    {
        return str_replace('{api_version}', static::API_VERSION, $endpoint);
    }

    /**
     * Prepares the transaction and sends it.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     * @param  \Transbank\Sdk\Credentials\Credentials  $credentials
     * @param  array  $options
     *
     * @return array
     * @throws \JsonException|\Transbank\Sdk\Exceptions\TransbankException
     */
    protected function sendRequest(
        Request $request,
        ApiRequest $apiRequest,
        Credentials $credentials,
        array $options = []
    ): array {
        $request = $this->prepareRequest($request, $apiRequest, $credentials, $options);

        try {
            $response = $this->client->sendRequest($request);
        } catch (NetworkExceptionInterface $exception) {
            throw new NetworkException(
                'Could not establish connection with Transbank.',
                $apiRequest,
                $request,
                null,
                $exception
            );
        } catch (Throwable $exception) {
            throw new UnknownException(
                'An error occurred when trying to communicate with Transbank.',
                $apiRequest,
                $request,
                null,
                $exception
            );
        }

        // If we received a response, check if the response is NOT an error.
        $this->throwExceptionOnResponseError($apiRequest, $request, $response);

        return $this->decodeJsonFromContents($apiRequest, $request, $response);
    }

    /**
     * Prepares the HTTP Request to send to the Transbank servers.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     * @param  \Transbank\Sdk\Credentials\Credentials  $credentials
     * @param  array  $options
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     * @noinspection CallableParameterUseCaseInTypeContextInspection
     */
    protected function prepareRequest(
        Request $request,
        ApiRequest $apiRequest,
        Credentials $credentials,
        array $options
    ): Request {
        // Let the developer override the credentials headers by adding them before anything else.
        $request = $request->withHeader(static::HEADER_KEY, $credentials->key);
        $request = $request->withHeader(static::HEADER_SECRET, $credentials->secret);

        // Pass any "option" headers to the request.
        if ($options && isset($options['headers'])) {
            foreach ($options['headers'] as $header => $value) {
                $request = $request->withHeader($header, $value);
            }
        }

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withHeader('User-Agent', 'SDK-PHP/' . Transbank::VERSION);

        $request = $request->withBody($this->streamFactory->createStream($apiRequest->toJson()));

        return $request;
    }

    /**
     * Checks if the Response is an error or not.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     *
     * @return void
     */
    protected function throwExceptionOnResponseError(ApiRequest $apiRequest, Request $request, Response $response): void
    {
        // The first barrier is a JSON response.
        if (!in_array('application/json', $response->getHeader('Content-Type'))) {
            throw new ServerException('Non-JSON response received.', $apiRequest, $request, $response);
        }

        $status = $response->getStatusCode();

        if ($status > 299) {
            if ($status < 400) {
                throw new ServerException('A redirection was returned.', $apiRequest, $request, $response);
            }

            if ($status < 500) {
                throw new ClientException($this->getErrorMessage($response), $apiRequest, $request, $response);
            }

            throw new ServerException($this->getErrorMessage($response), $apiRequest, $request, $response);
        }
    }

    /**
     * Returns the error message from the Transbank response.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return string
     */
    protected function getErrorMessage(Response $response): string
    {
        $contents = json_decode($response->getBody()->getContents(), true, 512, JSON_ERROR_NONE) ?? [];

        return $contents['error_message'] ?? $response->getBody()->getContents();
    }

    /**
     * Parses the JSON from the response, or bails if is malformed.
     *
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return array
     */
    protected function decodeJsonFromContents(
        ApiRequest $apiRequest,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): array {
        if (empty($contents = $response->getBody()->getContents())) {
            return [];
        }

        try {
            return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ServerException('The response JSON is malformed.', $apiRequest, $request, $response, $exception);
        }
    }
}
