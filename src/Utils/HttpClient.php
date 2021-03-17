<?php
namespace Transbank\Utils;

use Composer\InstalledVersions;
use GuzzleHttp\Client;
use GuzzleHttp\Message\FutureResponse;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Ring\Future\FutureInterface;

class HttpClient
{
    /**
     * @param $url
     * @param $path
     * @param null $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($url, $path, $options = null)
    {
        return $this->perform('GET', $url . $path, [], $options);
    }

    /**
     * @param $url
     * @param $path
     * @param $data_to_send
     * @param null $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($url, $path, $data_to_send, $options = null)
    {
        return $this->perform('POST', $url . $path, $data_to_send, $options);
    }


    /**
     * @param $url
     * @param $path
     * @param $data_to_send
     * @param null $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($url, $path, $data_to_send, $options = null)
    {
        return $this->perform('PUT', $url . $path, $data_to_send, $options);
    }

    /**
     * @param $url
     * @param $path
     * @param $data_to_send
     * @param null $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($url, $path, $data_to_send, $options = null)
    {
        return $this->perform('DELETE', $url . $path, $data_to_send, $options);
    }

    /**
     * @param $url
     * @param $path
     * @param $options
     * @param $method
     * @param $data_to_send
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function perform($method, $url, $data_to_send = [], $options = null)
    {
        $installedVersion = 'unknown';
        try {
            $installedVersion = InstalledVersions::getVersion('transbank/transbank-sdk');
        } catch (\Exception $exception) {
        }
        
        $basicHeader = [
            "Content-Type" => "application/json",
            "User-Agent" => 'SDK-PHP/' . $installedVersion
        ];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);
        if (!$data_to_send) {
            $data_to_send = null;
        }
        if (is_array($data_to_send)) {
            $data_to_send = json_encode($data_to_send);
        }

        if (defined('\GuzzleHttp\Client::VERSION') && version_compare(Client::VERSION, '6', '<')) {
            $client = new Client();
            return $client->request($method, $url, [
                'headers' => $headers,
                'body' => $data_to_send
            ]);
        }
        $request = new Request($method, $url, $headers, $data_to_send);
        $client = new Client(['http_errors' => false]);
        return $client->send($request);
    }
}
