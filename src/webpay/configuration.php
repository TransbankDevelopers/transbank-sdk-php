<?php

namespace Transbank\Webpay;

use Exception;

/**
 * Class Configuration
 *
 * @package Transbank\Webpay
 *
 * @method static Configuration forTestingWebpayPlusNormal()
 * @method static Configuration forTestingWebpayPlusMall()
 * @method static Configuration forTestingWebpayPlusCapture()
 * @method static Configuration forTestingWebpayOneClickNormal()
 * @method static Configuration forTestingPatPassByWebpayNormal()
 */
class Configuration
{

    /** @var string */
    protected $environment;

    /** @var string|int */
    protected $commerceCode;

    /** @var string */
    protected $privateKey;

    /** @var string */
    protected $publicCert;

    /** @var string */
    protected $webpayCert;

    /**
     * @deprecated
     * @var array|null
     */
    protected $storeCodes;

    /** @var string|null */
    protected $commerceMail;

    /**
     * Configuration constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // Let's create an array to save the attributes, and fill it with the passed ones.
        // If there is no attributes, no problem, every attribute will be null until
        // they are later filled manually.
        $attributes = array_merge(array_flip([
            'environment', 'commerceCode', 'privateKey', 'publicCert',
            'webpayCert', 'storeCodes', 'commerceMail'
        ]), $attributes);

        $this->environment = $attributes['environment'];
        $this->commerceCode = $attributes['commerceCode'];
        $this->privateKey = $attributes['privateKey'];
        $this->publicCert = $attributes['publicCert'];
        $this->webpayCert = $attributes['webpayCert'];
        $this->storeCodes = $attributes['storeCodes'];
        $this->commerceMail = $attributes['commerceMail'];
    }

    /**
     * This method does absolutely nothing, but I will leave it here
     * as deprecated
     *
     * @deprecated
     */
    public function Configuration()
    {
    }

    /**
     * Gets the Environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Sets the Environment
     *
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Gets the Environment
     *
     * @return int|string
     */
    public function getCommerceCode()
    {
        return $this->commerceCode;
    }

    /**
     * Sets the Commerce Code
     *
     * @param string $commerceCode
     */
    public function setCommerceCode($commerceCode)
    {
        $this->commerceCode = $commerceCode;
    }

    /**
     * Get the Private Key
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Sets the Private Key
     *
     * @param string $protected_key
     */
    public function setPrivateKey($protected_key)
    {
        $this->privateKey = $protected_key;
    }

    /**
     * Gets the Commerce Public Certificate
     *
     * @return string
     */
    public function getPublicCert()
    {
        return $this->publicCert;
    }

    /**
     * Sets the Commerce Public Certificate
     *
     * @param $publicCert
     */
    public function setPublicCert($publicCert)
    {
        $this->publicCert = $publicCert;
    }

    /**
     * Gets the Webpay Public Certificate
     *
     * @return string
     */
    public function getWebpayCert()
    {
        return $this->webpayCert;
    }

    /**
     * Sets the Webpay Public Certificate
     *
     * @param $webpayCert
     */
    public function setWebpayCert($webpayCert)
    {
        $this->webpayCert = $webpayCert;
    }

    /**
     * Get the Store Codes
     *
     * Dunno why someone would use this if there is no code that uses it.
     * I'll leave it as deprecated
     *
     * @deprecated
     * @return string
     */
    public function getStoreCodes()
    {
        return $this->storeCodes;
    }

    /**
     * Sets the storeCodes
     *
     * Same as before, the code is not used anywhere
     *
     * @deprecated
     * @param array $storeCodes
     */
    public function setStoreCodes(array $storeCodes)
    {
        $this->storeCodes = $storeCodes;
    }

    /**
     * Sets the Commerce Email
     *
     * @param string $commerceMail
     */
    public function setCommerceMail($commerceMail)
    {
        $this->commerceMail = $commerceMail;
    }

    /**
     * Gets the Commerce Email
     *
     * @return null|string
     */
    public function getCommerceMail()
    {
        return $this->commerceMail;
    }

    /**
     * Returns the Default Environment for Webpay
     *
     * This was used by the Transactions to select the URLs for SOAP, but since
     * now it uses the getEnvironment directly, its no use. I'll leave it as
     * deprecated just in case, because is written as a public accessible.
     *
     * @deprecated
     * @return string
     */
    public function getEnvironmentDefault()
    {
        $mode = $this->environment;
        if (!isset($this->environment) || empty($this->environment)) {
            $mode = 'INTEGRACION';
        }
        return $mode;
    }

    /**
     * Forward a static call
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static)->forwardCall($name, $arguments);
    }

    /**
     * Forward a dynamic call
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        return $this->forwardCall($name, $arguments);
    }

    /**
     * Dynamically call IntegrationConfiguration for testing Transactions
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    protected function forwardCall($name, $arguments)
    {
        if (method_exists(IntegrationConfiguration::class, $name)) {
            return IntegrationConfiguration::{$name}($arguments);
        }

        throw new Exception("Method $name doesn't exists in this class");
    }

}
