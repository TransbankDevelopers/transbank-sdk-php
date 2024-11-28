<?php

namespace Transbank\Contracts;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;

interface HttpClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param array|null $payload
     * @param array|null $options
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    public function request(
        string $method,
        string $url,
        array|null $payload = [],
        array|null $options = null
    ): ResponseInterface;
}
