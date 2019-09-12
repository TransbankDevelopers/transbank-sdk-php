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
    const DEFAULT_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
    const DEFAULT_TRANSACCION_COMPLETA_COMMERCE_CODE = '597055555530';
    const DEFAULT_TRANSACCION_COMPLETA_MALL_COMMERCE_CODE = '597055555551';
    const DEFAULT_TRANSACCION_COMPLETA_MALL_CHILD_COMMERCE_CODE = array('597055555552', '597055555553');
    const DEFAULT_INTEGRATION_TYPE = "TEST";

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
        return new Options(self::DEFAULT_API_KEY, self::DEFAULT_TRANSACCION_COMPLETA_COMMERCE_CODE);
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
     * @return Options
     */
    public function setIntegrationType($integrationType)
    {
        $this->integrationType = $integrationType;
        return $this;
    }
}
