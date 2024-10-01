<?php

namespace Transbank\Utils\Curl;

use Composer\InstalledVersions;
use Transbank\Contracts\HttpClientInterface;
use Psr\Http\Message\ResponseInterface;


class HttpCurlClient implements HttpClientInterface
{
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

        $request = new Request($method, $url, $headers, $payload);
        $client = new Client($requestTimeout);
        return $client->sendRequest($request);
    }
}
