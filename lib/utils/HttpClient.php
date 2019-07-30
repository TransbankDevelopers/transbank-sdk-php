<?php
namespace Transbank\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class HttpClient {

    function post($url, $path, $data_to_send, $options = null) {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);

        $req = new Request('POST', $fullPath,  $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        $res = $cl->send($req);
        return $res;
    }

    function put($url, $path, $data_to_send, $options = null)
    {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);

        $req = new Request('PUT', $fullPath,  $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        $res = $cl->send($req);
        return $res;
    }

    function get($url, $path, $options = null)
    {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);

        $req = new Request('GET', $fullPath,  $headers);
        $cl = new Client(['http_errors' => false]);

        $res = $cl->send($req);
        return $res;
    }

    public function delete($url, $path, $data_to_send, $options = null)
    {
        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $givenHeaders = isset($options["headers"]) ? $options["headers"] : [];
        $headers = array_merge($basicHeader, $givenHeaders);

        $req = new Request('DELETE', $fullPath,  $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        $res = $cl->send($req);
        return $res;
    }
}
