<?php

namespace Transbank\Utils;

use Transbank\Contracts\RequestService;

/**
 * Trait RequestServiceTrait .
 */

trait RequestServiceTrait
{
    /**
     * @var RequestService |null
     */
    protected $requestService;

    /**
     * @param $method
     * @param $endpoint
     * @param array|null $payload
     *
     * @throws \Transbank\Webpay\Exceptions\WebpayRequestException
     *
     * @return mixed
     */
    public function sendRequest($method, $endpoint, $payload = [])
    {
        return $this->getRequestService()->request(
            $method,
            $endpoint,
            $payload,
            $this->getOptions()
        );
    }

    /**
     * @return RequestService |null
     */
    public function getRequestService()
    {
        return $this->requestService;
    }

    /**
     * @param RequestService |null $requestService
     */
    public function setRequestService(RequestService $requestService = null)
    {
        $this->requestService = $requestService;
    }
}
