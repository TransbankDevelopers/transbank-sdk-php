<?php

namespace Transbank\Utils;

class TransbankApiRequest
{
    public string $method;
    public string $baseUrl;
    public string $endpoint;
    public array $payload;
    public array $headers;

    /**
     * FailedRequestCapturedData constructor.
     *
     * @param string $method
     * @param string $baseUrl
     * @param string $endpoint
     * @param array $payload
     * @param array $headers
     */
    public function __construct(
        string $method,
        string $baseUrl,
        string $endpoint,
        array $payload = [],
        array $headers = []
    ) {
        $this->method = $method;
        $this->baseUrl = $baseUrl;
        $this->endpoint = $endpoint;
        $this->payload = $payload;
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
