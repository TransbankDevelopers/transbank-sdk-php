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
     * @var string $apiKey Your api key, given by Transbank
     */
    public $apiKey = null;
    /**
     * @var string $commerceCode Your commerce code, given by Transbank
     */
    public $commerceCode = null;

    const DEFAULT_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
    const DEFAULT_COMMERCE_CODE = '597055555532';

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

}
