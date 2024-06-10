<?php

namespace Transbank\Utils;

use Transbank\Contracts\RequestService;
use Transbank\Webpay\Options;

/**
 * Trait InteractsWithWebpayApi.
 */
trait InteractsWithWebpayApi
{
    /**
     * @var Options
     */
    protected $options;
    /**
     * @var RequestService |null
     */
    protected $requestService;

    /**
     * Transaction constructor.
     *
     * @param Options              $options
     * @param RequestService |null $requestService
     */
    public function __construct(
        Options $options,
        RequestService $requestService = null
    ) {
        $this->options = $options;

        $this->setRequestService($requestService !== null ? $requestService :
            new HttpClientRequestService());
    }

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
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Options $options
     */
    public function setOptions(Options $options)
    {
        $this->options = $options;
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

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return $this->getOptions()->getApiBaseUrl();
    }

    /**
     * @param $commerceCode
     * @param $apiKey
     *
     * @return $this
     */
    public static function buildForIntegration($commerceCode, $apiKey)
    {
        return new static(new Options($apiKey, $commerceCode, Options::ENVIRONMENT_INTEGRATION));
    }

    /**
     * @param $commerceCode
     * @param $apiKey
     *
     * @return $this
     */
    public static function buildForProduction($commerceCode, $apiKey)
    {
        return new static(new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION));
    }
}
