<?php

namespace Transbank\Webpay;

/**
 * Class Options.
 * @var string ENVIRONMENT_PRODUCTION
 * @var string ENVIRONMENT_INTEGRATION
 * @var string BASE_URL_PRODUCTION
 * @var string BASE_URL_INTEGRATION
 * @var string INTEGRATION_API_KEY
 * @var int DEFAULT_TIMEOUT
 */
class Options
{
    const ENVIRONMENT_PRODUCTION = 'LIVE';
    const ENVIRONMENT_INTEGRATION = 'TEST';
    const BASE_URL_PRODUCTION = 'https://webpay3g.transbank.cl/';
    const BASE_URL_INTEGRATION = 'https://webpay3gint.transbank.cl/';

    const INTEGRATION_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';

    const DEFAULT_TIMEOUT = 60 * 10;

    /**
     * @var int Timeout for requests in seconds
     */
    protected int $timeout;

    /**
     * @var ?string Your api key, given by Transbank.Sent as a header when
     *             making requests to Transbank on a field called "Tbk-Api-Key-Secret"
     */
    public ?string $apiKey = null;
    /**
     * @var ?string Your commerce code, given by Transbank. Sent as
     *             a header when making requests to Transbank on a field called "Tbk-Api-Key-Id"
     */
    public ?string $commerceCode = null;
    /**
     * @var string Sets the environment that the SDK is going
     *             to point to (eg. TEST, LIVE, etc).
     */
    public string $integrationType = self::ENVIRONMENT_INTEGRATION;

    public function __construct(
        string $apiKey,
        string $commerceCode,
        string $integrationType,
        int $timeout = self::DEFAULT_TIMEOUT
    ) {
        $this->apiKey = $apiKey;
        $this->commerceCode = $commerceCode;
        $this->integrationType = $integrationType;
        $this->timeout = $timeout;
    }

    /**
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->getIntegrationType() === static::ENVIRONMENT_PRODUCTION;
    }

    /**
     * @return ?string
     */
    public function getIntegrationType(): ?string
    {
        return $this->integrationType;
    }

    /**
     * @param string $integrationType
     *
     * @return Options
     */
    public function setIntegrationType($integrationType): Options
    {
        $this->integrationType = $integrationType;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return Options
     */
    public function setApiKey(string $apiKey): Options
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getCommerceCode(): ?string
    {
        return $this->commerceCode;
    }

    /**
     * @param string $commerceCode
     *
     * @return Options
     */
    public function setCommerceCode(string $commerceCode): Options
    {
        $this->commerceCode = $commerceCode;

        return $this;
    }

    /**
     * @return string Returns the base URL used for making requests, depending on which
     *                integration types
     */
    public function getApiBaseUrl(): string
    {
        if ($this->isProduction()) {
            return static::BASE_URL_PRODUCTION;
        }
        return static::BASE_URL_INTEGRATION;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Tbk-Api-Key-Id'     => $this->getCommerceCode(),
            'Tbk-Api-Key-Secret' => $this->getApiKey(),
        ];
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     *
     * @return Options
     */
    public function setTimeout($timeout): Options
    {
        $this->timeout = $timeout;

        return $this;
    }
}
