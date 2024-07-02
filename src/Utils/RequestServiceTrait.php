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
     * @var ?RequestService
     */
    protected ?RequestService $requestService = null;

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
     * @return ?RequestService
     */
    public function getRequestService(): ?RequestService
    {
        return $this->requestService;
    }

    /**
     * @param ?RequestService $requestService
     */
    public function setRequestService(?RequestService $requestService = null): void
    {
        $this->requestService = $requestService;
    }
}
