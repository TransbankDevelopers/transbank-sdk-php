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
    protected Options $options;

    /**
     * Transaction constructor.
     *
     * @param Options         $options
     * @param RequestService|null $requestService
     */
    public function __construct(
        Options $options,
        RequestService|null $requestService = null
    ) {
        $this->options = $options;

        $this->setRequestService($requestService !== null ? $requestService :
            new HttpClientRequestService());
    }

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * @param Options $options
     */
    public function setOptions(Options $options): void
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return $this->getOptions()->getApiBaseUrl();
    }

    /**
     * Build an instance configured for integration environment.
     * @param string $apiKey
     * @param string $commerceCode
     *
     * @return static
     */
    public static function buildForIntegration(string $apiKey, string $commerceCode): self
    {
        return new static(new Options($apiKey, $commerceCode, Options::ENVIRONMENT_INTEGRATION));
    }

    /**
     * Build an instance configured for production environment.
     * @param string $apiKey
     * @param string $commerceCode
     *
     * @return static
     */
    public static function buildForProduction(string $apiKey, string $commerceCode): self
    {
        return new static(new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION));
    }
}
