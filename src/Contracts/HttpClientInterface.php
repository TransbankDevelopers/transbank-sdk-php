<?php

namespace Transbank\Contracts;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param array|null $payload
     * @param array|null $options
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
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
