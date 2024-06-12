<?php

namespace Transbank\Utils;

use Transbank\Contracts\RequestService;
use Transbank\Webpay\Options;

/**
 * Trait InteractsWithWebpayApi.
 */
trait InteractsWithWebpayApi
{
    use RequestServiceTrait;

    /**
     * @var Options
     */
    protected $options;

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
