<?php

namespace Transbank\Contracts;

use Transbank\Webpay\Options;
use Psr\Http\Message\ResponseInterface;
use Transbank\Webpay\Exceptions\TransbankApiRequest;

interface RequestService
{
    /**
     * @param string  $method
     * @param string  $endpoint
     * @param array   $payload
     * @param Options $options
     *
     * @throws \Transbank\Webpay\Exceptions\WebpayRequestException
     *
     * @return array Response from the API as json.
     */
    public function request(
        string $method,
        string $endpoint,
        array $payload,
        Options $options
    ): array;
    public function getLastResponse(): ResponseInterface|null;
    public function getLastRequest(): TransbankApiRequest|null;
}
