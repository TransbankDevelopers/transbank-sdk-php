<?php

namespace Transbank\Utils;

use Transbank\Contracts\RequestService;
use Transbank\Webpay\Exceptions\WebpayRequestException;

/**
 * Trait RequestServiceTrait .
 */

trait RequestServiceTrait
{
    /**
     * @var RequestService|null
     */
    protected RequestService|null $requestService = null;

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $payload
     *
     * @throws WebpayRequestException
     *
     * @return array
     */
    public function sendRequest(string $method, string $endpoint, array $payload = []): array
    {
        return $this->getRequestService()->request(
            $method,
            $endpoint,
            $payload,
            $this->getOptions()
        );
    }

    /**
     * @return RequestService|null
     */
    public function getRequestService(): RequestService|null
    {
        return $this->requestService;
    }

    /**
     * @param RequestService|null $requestService
     */
    public function setRequestService(RequestService|null $requestService = null): void
    {
        $this->requestService = $requestService;
    }
}
