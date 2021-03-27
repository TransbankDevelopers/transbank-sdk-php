<?php

namespace Transbank\Sdk\Services;

use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Http\Connector;

trait SendsRequests
{
    use HandlesCredentials;

    /**
     * Sends a ApiRequest to Transbank, returns a response array.
     *
     * @param  string  $action
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     * @param  string  $method
     * @param  string  $endpoint
     * @param  array  $replace
     * @param  array  $options
     *
     * @return array
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    protected function send(
        string $action,
        ApiRequest $apiRequest,
        string $method,
        string $endpoint,
        array $replace = [],
        array $options = []
    ): array {
        return $this->transbank->connector->send(
            $method,
            $this->buildEndpoint($endpoint, $replace),
            $apiRequest,
            $this->getEnvironmentCredentials($action),
            $options
        );
    }

    /**
     * Builds the endpoint, depending on the environment, and replaces keys from it.
     *
     * @param  string  $endpoint
     * @param  array  $replace
     *
     * @return string
     */
    protected function buildEndpoint(string $endpoint, array $replace = []): string
    {
        return str_replace(array_keys($replace), $replace, $this->transbank->isProduction()
            ? Connector::PRODUCTION_ENDPOINT . $endpoint
            : Connector::INTEGRATION_ENDPOINT . $endpoint, $replace);
    }
}
