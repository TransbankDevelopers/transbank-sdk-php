<?php


namespace Transbank\Patpass;

use Transbank\PatPass\Commerce\patpassComercio;

/**
 * Class Options
 *
 * @package Transbank\Patpass
 */
class Options
{
<<<<<<< HEAD
    /**
     * Default API key (which is sent as a header when making requests to Transbank
     * on a field called "Tbk-Api-Key-Secret")
     */
    const DEFAULT_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
    const DEFAULT_INTEGRATION_TYPE = "TEST";
    const DEFAULT_PATPASS_BY_WEBPAY_COMMERCE_CODE = '597055555550';
    const DEFAULT_PATPASS_COMMERCE_COMMERCE_CODE = '';
=======
    const DEFAULT_PATPASS_API_KEY = "";

    const DEFAULT_PATPASS_INTEGRATION_TYPE = "TEST";
    const DEFAULT_PATPASS_INTEGRATION_TYPE_URL = "https://webpay3gint.transbank.cl/";
    const DEFAULT_PATPASS_COMMERCE_COMMERCE_CODE = "";
    const DEFAULT_PATPASS_NAME = "";
    const DEFAULT_PATPASS_COMMERCE_EMAIL = "";
>>>>>>> parent of b99096a... Merge branch 'fix/remove_unnecessary_error_checks_in_body' into feat/patpass-commerce

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
            self::DEFAULT_API_KEY,
            self::DEFAULT_PATPASS_COMMERCE_COMMERCE_CODE
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
        return patpassComercio::$INTEGRATION_TYPES[$this->integrationType];
    }
}