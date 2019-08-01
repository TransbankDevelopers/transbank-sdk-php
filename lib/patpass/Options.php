<?php


namespace Transbank\Patpass;

use Transbank\Patpass;

/**
 * Class Options
 *
 * @package Transbank\Patpass
 */
class Options
{
    const DEFAULT_PATPASS_API_KEY = "giPmNIQIabX8KADNqJht   ";

    const DEFAULT_PATPASS_INTEGRATION_TYPE = "TEST";
    const DEFAULT_PATPASS_INTEGRATION_TYPE_URL = "https://webpay3gint.transbank.cl/";
    const DEFAULT_PATPASS_COMERCIO_COMMERCE_CODE = "28299257";

    public $apiKey = null;
    public $commerceCode = null;
    public $integrationType = "TEST";

    public function __construct($apiKey, $commerceCode)
    {
        $this->apiKey = $apiKey;
        $this->commerceCode = $commerceCode;
    }

    public static function defaultConfig()
    {
        return new Options(
            self::DEFAULT_PATPASS_API_KEY,
            self::DEFAULT_PATPASS_COMERCIO_COMMERCE_CODE
        );
    }

    /**
     * @return null
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param null $apiKey
     * @return Options
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return null
     */
    public function getCommerceCode()
    {
        return $this->commerceCode;
    }

    /**
     * @param null $commerceCode
     * @return Options
     */
    public function setCommerceCode($commerceCode)
    {
        $this->commerceCode = $commerceCode;
        return $this;
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
     */
    public function setIntegrationType($integrationType)
    {
        $this->integrationType = $integrationType;
    }

    /**
     * @return string  Returns the base URL used for making requests, depending on which
     * integration types
     */
    public function integrationTypeUrl()
    {
        return PatpassComercio::$INTEGRATION_TYPES[$this->integrationType];
    }
}
