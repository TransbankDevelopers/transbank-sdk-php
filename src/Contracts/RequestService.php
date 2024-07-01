<?php

namespace Transbank\Contracts;

use Transbank\Webpay\Options;
use Transbank\Webpay\Exceptions\WebpayRequestException;

interface RequestService
{
    /**
     * @param string  $method
     * @param string  $endpoint
     * @param array   $payload
     * @param Options $options
     *
     * @throws WebpayRequestException
     *
     * @return array Response from the API as json.
     */
    public function request(string $method, string $endpoint, array $payload, Options $options);
}
