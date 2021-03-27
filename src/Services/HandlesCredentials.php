<?php

namespace Transbank\Sdk\Services;

use Transbank\Sdk\Credentials\Credentials;

/**
 * Trait HandlesCredentials
 *
 * @package Transbank\Sdk\Services
 */
trait HandlesCredentials
{
    /**
     * Returns the set of credentials for the current environment.
     *
     * @param  string  $overrideServiceName
     *
     * @return Credentials
     */
    protected function getEnvironmentCredentials(string $overrideServiceName): Credentials
    {
        if ($this->transbank->isProduction()) {
            return $this->credentials;
        }

        // If we're running on integration, there is no harm on creating new credentials for each request.
        return Credentials::integrationCredentials($overrideServiceName);
    }
}
