<?php

namespace Transbank\Utils;

use Transbank\Contracts\HttpClientInterface;
use Transbank\Contracts\RequestService;
use Transbank\Webpay\Exceptions\TransbankApiRequest;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpClientRequestService implements RequestService
{
    /**
     * @var ResponseInterface|null
     */
    protected ResponseInterface|null $lastResponse = null;

    /**
     * @var ?TransbankApiRequest
     */
    protected ?TransbankApiRequest $lastRequest = null;

    /**
     * @var HttpClientInterface
     */
    protected ?HttpClientInterface $httpClient;

    public function __construct(?HttpClientInterface $httpClient = null)
    {
        $this->setHttpClient($httpClient ?? new HttpClient());
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     *
     * @return void
     */
    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string  $method
     * @param string  $endpoint
     * @param array   $payload
     * @param Options $options
     *
     * @throws GuzzleException
     * @throws WebpayRequestException
     *
     * @return array
     */
    public function request(
        string $method,
        string $endpoint,
        array $payload,
        Options $options
    ): array {
        $headers = $options->getHeaders();
        $client = $this->httpClient;
        if ($client == null) {
            $client = new HttpClient();
        }

        $baseUrl = $options->getApiBaseUrl();
        $request = new TransbankApiRequest($method, $baseUrl, $endpoint, $payload, $headers);

        $this->setLastRequest($request);
        $response = $client->request($method, $baseUrl . $endpoint, $payload, [
            'headers' => $headers,
            'timeout' => $options->getTimeout()
        ]);

        $this->setLastResponse($response);
        $responseStatusCode = $response->getStatusCode();

        if (!in_array($responseStatusCode, [200, 204])) {
            $reason = $response->getReasonPhrase();
            $message = "Could not obtain a response from Transbank API: $reason (HTTP code $responseStatusCode)";
            $body = json_decode($response->getBody(), true);
            $tbkErrorMessage = $body['error_message'] ?? null;

            throw new WebpayRequestException($message, $tbkErrorMessage, $responseStatusCode, $request);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse(): ResponseInterface|null
    {
        return $this->lastResponse;
    }

    /**
     * @param ResponseInterface|null $lastResponse
     */
    protected function setLastResponse(ResponseInterface|null $lastResponse): void
    {
        $this->lastResponse = $lastResponse;
    }

    /**
     * @return ?TransbankApiRequest
     */
    public function getLastRequest(): ?TransbankApiRequest
    {
        return $this->lastRequest;
    }

    /**
     * @param ?TransbankApiRequest $lastRequest
     */
    protected function setLastRequest(?TransbankApiRequest $lastRequest): void
    {
        $this->lastRequest = $lastRequest;
    }
}
