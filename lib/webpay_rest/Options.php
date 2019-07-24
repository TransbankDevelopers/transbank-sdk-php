<?php


namespace Transbank\Webpay;


/**
 * Class Options
 *
 * @package Transbank\Webpay
 */
class Options
{
    /**
     * Default API key (which is sent as a header when making requests to Transbank
     * on a field called "Tbk-Api-Key-Secret")
     */
    const DEFAULT_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
    /**
     *
     */
    const DEFAULT_COMMERCE_CODE = '597055555532';
    const DEFAULT_INTEGRATION_TYPE = "TEST";
    const DEFAULT_INTEGRATION_TYPE_URL = "https://webpay3gint.transbank.cl/";
    /**
     * @var string $apiKey Your api key, given by Transbank.Sent as a header when
     * making requests to Transbank on a field called "Tbk-Api-Key-Secret"
     */
    public $apiKey = null;
    /**
     * @var string $commerceCode Your commerce code, given by Transbank. Sent as
     * a header when making requests to Transbank on a field called "Tbk-Api-Key-Id"
     */
    public $commerceCode = null;
    /**
     * @var string $integrationType Sets the environment that the SDK is going
     * to point to (eg. TEST, LIVE, etc).
     */
    public $integrationType = 'TEST';

    public function __construct($apiKey, $commerceCode)
    {
        $this->apiKey = $apiKey;
        $this->commerceCode = $commerceCode;
    }

    /**
     * @return Options Return an instance of Options with default values
     * configured
     */
    public static function defaultConfig()
    {
        return new Options(self::DEFAULT_API_KEY,
            self::DEFAULT_COMMERCE_CODE);
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
     * integration types
     */
    public function integrationTypeUrl()
    {
        return WebpayPlus::$INTEGRATION_TYPES[$this->integrationType];
    }


}
