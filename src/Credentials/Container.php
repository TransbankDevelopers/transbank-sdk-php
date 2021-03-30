<?php

namespace Transbank\Sdk\Credentials;

use LogicException;
use RuntimeException;

/**
 * Class Container
 * ---
 *
 * This class works as a "container" for all services' credentials. This makes
 * easy for each service to get the credentials using a common interface.
 *
 * @package Transbank\Sdk\Credentials
 */
class Container
{
    /**
     * Credentials for Webpay.
     *
     * @var Credentials|null
     */
    protected $webpay = null;

    /**
     * Credentials for Webpay Mall.
     *
     * @var Credentials|null
     */
    protected $webpayMall = null;

    /**
     * Credentials for Oneclick Mall.
     *
     * @var Credentials|null
     */
    protected $oneclickMall = null;

    /**
     * Credentials for Full ApiRequest.
     *
     * @var Credentials|null
     */
    protected $fullTransaction = null;

    /**
     * Fills the credentials for each service.
     *
     * @param  array  $credentials
     *
     * @return void
     */
    public function setFromArray(array $credentials): void
    {
        foreach ($credentials as $service => $credential) {
            // Check if the service name exists. If not, bail.
            $this->throwWhenCredentialsDoesNotExists($service);

            // We need the array declaring the key and the secret. If not, bail.
            if (!isset($credential['key'], $credential['secret'])) {
                throw new LogicException("Credentials for [$service] must have a [key] and [secret].");
            }

            $this->{$service} = new Credentials($credential['key'], $credential['secret']);
        }
    }

    /**
     * Checks that credentials for a service name exists.
     *
     * @param  string  $service
     *
     * @throws \LogicException
     */
    protected function throwWhenCredentialsDoesNotExists(string $service): void
    {
        if (!property_exists($this, $service)) {
            throw new LogicException("The Transbank service [$service] doesn't exist for these credentials.");
        }
    }

    /**
     * Returns the credentials for a given service.
     *
     * @param  string  $service
     *
     * @return \Transbank\Sdk\Credentials\Credentials
     */
    public function getProductionCredentials(string $service): Credentials
    {
        $this->throwWhenCredentialsDoesNotExists($service);

        if (!$this->{$service}) {
            throw new RuntimeException("Production credentials for [$service] are not set.");
        }

        return $this->{$service};
    }
}
