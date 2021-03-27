<?php

namespace Transbank\Sdk\Credentials;

use LogicException;

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
     * @var Credentials
     */
    public Credentials $webpay;

    /**
     * Credentials for Webpay Mall.
     *
     * @var Credentials
     */
    public Credentials $webpayMall;

    /**
     * Credentials for Oneclick Mall.
     *
     * @var Credentials
     */
    public Credentials $oneclickMall;

    /**
     * Credentials for Patpass.
     *
     * @var Credentials
     */
    public Credentials $patpass;

    /**
     * Credentials for Full ApiRequest.
     *
     * @var Credentials
     */
    public Credentials $fullTransaction;

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
     * Returns the credentials for a given service.
     *
     * @param  string  $service
     *
     * @return \Transbank\Sdk\Credentials\Credentials
     */
    public function getCredentials(string $service): Credentials
    {
        $this->throwWhenCredentialsDoesNotExists($service);

        return $this->{$service};
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
}
