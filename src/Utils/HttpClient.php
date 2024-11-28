<?php

namespace Transbank\Utils;

use Composer\InstalledVersions;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\GuzzleException;
use Transbank\Contracts\HttpClientInterface;
use Psr\Http\Message\ResponseInterface;


class HttpClient implements HttpClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param array|null $payload
     * @param array|null $options
     *
     *@throws GuzzleException
     *
     * @return ResponseInterface
     */
    public function request(
        string $method,
        string $url,
        array|null $payload = [],
        array|null $options = null
    ): ResponseInterface {
        $installedVersion = 'unknown';

        if (class_exists('\Composer\InstalledVersions') && InstalledVersions::isInstalled('transbank/transbank-sdk')) {
            $installedVersion = InstalledVersions::getVersion('transbank/transbank-sdk') ?? 'unknown';
        }

        $baseHeaders = [
            'Content-Type' => 'application/json',
            'User-Agent'   => 'SDK-PHP/' . $installedVersion,
        ];

        $givenHeaders = $options['headers'] ?? [];
        $headers = array_merge($baseHeaders, $givenHeaders);
        if (!$payload) {
            $payload = null;
        }
        if (is_array($payload)) {
            $payload = json_encode($payload);
        }

        $requestTimeout = $options['timeout'] ?? 0;

        return $this->sendGuzzleRequest($method, $url, $headers, $payload, $requestTimeout);
    }

    /**
     * Sends a Guzzle request.
     *
     * @param string  $method
     * @param string  $url
     * @param array   $headers
     * @param string|null $payload
     * @param int     $timeout
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    protected function sendGuzzleRequest(
        string $method,
        string $url,
        array $headers,
        string|null $payload,
        int $timeout
    ): ResponseInterface {
        $request = new Request($method, $url, $headers, $payload);

        $client = new Client([
            'http_errors' => false,
            'timeout' => $timeout,
            'read_timeout' => $timeout,
            'connect_timeout' => $timeout,
        ]);

        return $client->send($request);
    }
}
