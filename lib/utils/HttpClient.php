<?php
namespace Transbank\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class HttpClient
{
    public function get($url, $path, $options = null)
    {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);

        if (defined('Client::VERSION') && version_compare(Client::VERSION, '6', '<')) {
            $client = new Client();
            return $client->get($fullPath, [
                'headers' => $headers
            ]);
        }
        $req = new Request('GET', $fullPath, $headers);
        $cl = new Client(['http_errors' => false]);

        return $cl->send($req);
    }

    public function post($url, $path, $data_to_send, $options = null)
    {
        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);

        if (defined('Client::VERSION') && version_compare(Client::VERSION, '6', '<')) {
            $client = new Client();

            return $client->post($fullPath, [
                'headers' => $headers,
                'body' => $data_to_send
            ]);
        }
        $req = new Request('POST', $fullPath, $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        return $cl->send($req);
    }

    /**
     * @param $url
     * @param $path
     * @param $data_to_send
     * @param null $options
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function put($url, $path, $data_to_send, $options = null)
    {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);
        $client = new Client();

        if (defined('Client::VERSION') && version_compare(Client::getGuzzleVersion, '6', '<')) {
            return $client->put($fullPath, [
                'headers' => $headers,
                'body' => $data_to_send
            ]);
        }

        $req = new Request('PUT', $fullPath, $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        return $cl->send($req);
    }



    public function delete($url, $path, $data_to_send, $options = null)
    {
        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);

        if (defined('Client::VERSION') && version_compare(Client::VERSION, '6', '<')) {
            $client = new Client();

            return $client->delete($fullPath, [
                'headers' => $headers,
                'body' => $data_to_send
            ]);
        }

        $req = new Request('DELETE', $fullPath, $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        return $cl->send($req);
    }
}
