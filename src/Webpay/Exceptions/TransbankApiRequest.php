<?php

namespace Transbank\Webpay\Exceptions;

class TransbankApiRequest
{
    public $method;
    public $baseUrl;
    public $endpoint;
    public $payload;
    public $headers;

    /**
     * FailedRequestCapturedData constructor.
     *
     * @param $method
     * @param $baseUrl
     * @param $endpoint
     * @param array $payload
     * @param array $headers
     */
    public function __construct($method, $baseUrl, $endpoint, $payload = [], $headers = [])
    {
        $this->method = $method;
        $this->baseUrl = $baseUrl;
        $this->endpoint = $endpoint;
        $this->payload = $payload;
        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
