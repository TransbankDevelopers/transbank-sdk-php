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
     * @param ?array $payload
     * @param ?array $options
     *
     *@throws GuzzleException
     *
     * @return ResponseInterface
     */
    public function request(
        string $method,
        string $url,
        ?array $payload = [],
        ?array $options = null
    ): ResponseInterface {
        $installedVersion = 'unknown';

        if (class_exists('\Composer\InstalledVersions')) {
            try {
                $installedVersion = InstalledVersions::getVersion('transbank/transbank-sdk');
            } catch (\Exception $e) {
            }
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

        if (defined('\GuzzleHttp\Client::VERSION') && version_compare(Client::VERSION, '6', '<')) {
            return $this->sendGuzzle5Request($method, $url, $headers, $payload, $requestTimeout);
        }

        return $this->sendGuzzleRequest($method, $url, $headers, $payload, $requestTimeout);
    }

    /**
     * Sends a Guzzle 5 request.
     *
     * @param string $method
     * @param string $url
     * @param array  $headers
     * @param ?array $payload
     * @param int    $timeout
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    protected function sendGuzzle5Request($method, $url, array $headers, $payload, int $timeout): ResponseInterface
    {
        $client = new Client([
            'timeout' => $timeout,
            'read_timeout' => $timeout,
            'connect_timeout' => $timeout,
        ]);

        $request = $client->createRequest($method, $url, [
            'headers' => $headers,
            'body'    => $payload,
        ]);

        return $client->send($request);
    }

    /**
     * Sends a Guzzle request.
     *
     * @param string  $method
     * @param string  $url
     * @param array   $headers
     * @param ?string $payload
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
        ?string $payload,
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
