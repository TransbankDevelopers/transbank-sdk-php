<?php


namespace Transbank;


abstract class Configuration
{
    public $apiKey;

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getCommerceCode()
    {
        return $this->commerceCode;
    }

    /**
     * @param string $commerceCode
     */
    public function setCommerceCode($commerceCode)
    {
        $this->commerceCode = $commerceCode;
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
     * @return string
     */
    public function getSharedSecret()
    {
        return $this->sharedSecret;
    }

    /**
     * @param string $sharedSecret
     */
    public function setSharedSecret($sharedSecret)
    {
        $this->sharedSecret = $sharedSecret;
    }
    public $commerceCode;
    public $integrationType;
    public $sharedSecret;

    public function __construct()
    {
    }


}
