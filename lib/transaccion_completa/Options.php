<?php

/**
 * Class Options
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


class Options
{
    const DEFAULT_API_KEY = '';
    const DEFAULT_COMMERCE_CODE = '';
    const DEFAULT_INTEGRATION_TYPE = "TEST";
    const DEFAULT_INTEGRATION_TYPE_URL = "https://wwww.pagoautomaticocontarjetas.cl/";

    public $apiKey = null;
    public $commerceCode = null;
    public $integrationType = 'TEST';

    public function __construct($apiKey, $commerceCode)
    {
        $this->setApiKey($apiKey);
        $this->setCommerceCode($commerceCode);
    }

    public static function defaultConfig()
    {
        return new Options(self::DEFAULT_API_KEY, self::DEFAULT_COMMERCE_CODE);
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
        return $this;
    }
}
