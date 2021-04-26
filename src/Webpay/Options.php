<?php

namespace Transbank\Webpay;

/**
 * Class Options.
 */
class Options
{
    const ENVIRONMENT_PRODUCTION = 'LIVE';
    const ENVIRONMENT_INTEGRATION = 'TEST';
    const DEFAULT_INTEGRATION_TYPE = self::ENVIRONMENT_INTEGRATION;

    const BASE_URL_PRODUCTION = 'https://webpay3g.transbank.cl/';
    const BASE_URL_INTEGRATION = 'https://webpay3gint.transbank.cl/';

    const DEFAULT_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';

    /**
     * @var string Your api key, given by Transbank.Sent as a header when
     *             making requests to Transbank on a field called "Tbk-Api-Key-Secret"
     */
    public $apiKey = null;
    /**
     * @var string Your commerce code, given by Transbank. Sent as
     *             a header when making requests to Transbank on a field called "Tbk-Api-Key-Id"
     */
    public $commerceCode = null;
    /**
     * @var string Sets the environment that the SDK is going
     *             to point to (eg. TEST, LIVE, etc).
     */
    public $integrationType = self::ENVIRONMENT_INTEGRATION;

    public function __construct($apiKey, $commerceCode, $integrationType = self::ENVIRONMENT_INTEGRATION)
    {
        $this->setApiKey($apiKey);
        $this->setCommerceCode($commerceCode);
        $this->setIntegrationType($integrationType);
    }

    public static function forProduction($commerceCode, $apiKey)
    {
        return new static($apiKey, $commerceCode, static::ENVIRONMENT_PRODUCTION);
    }

    public static function forIntegration($commerceCode, $apiKey = Options::DEFAULT_API_KEY)
    {
        return new static($apiKey, $commerceCode, static::ENVIRONMENT_INTEGRATION);
    }

    public function isProduction()
    {
        return $this->getIntegrationType() === static::ENVIRONMENT_PRODUCTION;
    }

    /**
     * @return string
     */
    public function getIntegrationType()
    {
        return $this->integrationType;
    }

    /**
     * @param string $integrationType
     *
     * @return Options
     */
    public function setIntegrationType($integrationType)
    {
        $this->integrationType = $integrationType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return Options
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommerceCode()
    {
        return $this->commerceCode;
    }

    /**
     * @param mixed $commerceCode
     *
     * @return Options
     */
    public function setCommerceCode($commerceCode)
    {
        $this->commerceCode = $commerceCode;

        return $this;
    }

    /**
     * @return string Returns the base URL used for making requests, depending on which
     *                integration types
     */
    public function getApiBaseUrl()
    {
        if ($this->isProduction()) {
            return static::BASE_URL_PRODUCTION;
        }

        return static::BASE_URL_INTEGRATION;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return [
            'Tbk-Api-Key-Id'     => $this->getCommerceCode(),
            'Tbk-Api-Key-Secret' => $this->getApiKey(),
        ];
    }
}
