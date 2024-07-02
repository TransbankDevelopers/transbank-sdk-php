<?php

namespace Transbank\Contracts;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;

interface HttpClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param ?array $payload
     * @param ?array $options
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    public function request(
        string $method,
        string $url,
        ?array $payload = [],
        ?array $options = null
    ): ResponseInterface;
}
