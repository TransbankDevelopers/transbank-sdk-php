<?php

namespace Transbank\Utils;

use Composer\InstalledVersions;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Transbank\Contracts\HttpClientInterface;

class HttpClient implements HttpClientInterface
{
    /**
     * @param $url
     * @param $path
     * @param $options
     * @param $method
     * @param $payload
     *
     *@throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $url, $payload = [], $options = null)
    {
        $installedVersion = 'unknown';

        if (class_exists('\Composer\InstalledVersions')) {
            try {
                $installedVersion = InstalledVersions::getVersion('transbank/transbank-sdk');
            } catch (\Exception $e) {
            }
        }

        $baseHeaders = [
            'Content-Type' => 'application/json',
            'User-Agent'   => 'SDK-PHP/'.$installedVersion,
        ];

        $givenHeaders = isset($options['headers']) ? $options['headers'] : [];
        $headers = array_merge($baseHeaders, $givenHeaders);
        if (!$payload) {
            $payload = null;
        }
        if (is_array($payload)) {
            $payload = json_encode($payload);
        }

        if (defined('\GuzzleHttp\Client::VERSION') && version_compare(Client::VERSION, '6', '<')) {
            return $this->sendGuzzle5Request($method, $url, $headers, $payload);
        }

        return $this->sendGuzzleRequest($method, $url, $headers, $payload);
    }

    /**
     * @param $method
     * @param $url
     * @param array $headers
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendGuzzle5Request($method, $url, array $headers, $payload)
    {
        $client = new Client();

        return $client->request($method, $url, [
            'headers' => $headers,
            'body'    => $payload,
        ]);
    }

    /**
     * @param $method
     * @param $url
     * @param array       $headers
     * @param string|null $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendGuzzleRequest($method, $url, array $headers, $payload)
    {
        $request = new Request($method, $url, $headers, $payload);

        $client = new Client(['http_errors' => false]);

        return $client->send($request);
    }
}
