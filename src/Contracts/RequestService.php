<?php

namespace Transbank\Contracts;

use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;

interface RequestService
{
    /**
     * @param $method
     * @param $endpoint
     * @param $payload
     * @param Options $options
     *
     * @throws WebpayRequestException
     *
     * @return array Response from the API as json.
     */
    public function request($method, $endpoint, $payload, Options $options);
}
