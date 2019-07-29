<?php
namespace Transbank\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class HttpClient {

    function post($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null)) {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $headers = array_merge($basicHeader, $options["headers"]);

        $req = new Request('POST', $fullPath,  $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        $res = $cl->send($req);
        return $res;
    }

    function put($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null))
    {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $headers = array_merge($basicHeader, $options["headers"]);

        $req = new Request('PUT', $fullPath,  $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        $res = $cl->send($req);
        return $res;
    }

    function get($url, $path, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null))
    {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $headers = array_merge($basicHeader, $options["headers"]);

        $req = new Request('GET', $fullPath,  $headers);
        $cl = new Client(['http_errors' => false]);

        $res = $cl->send($req);
        return $res;
    }

    public function delete($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null))
    {
        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $headers = array_merge($basicHeader, $options["headers"]);

        $req = new Request('DELETE', $fullPath,  $headers, $data_to_send);
        $cl = new Client(['http_errors' => false]);

        $res = $cl->send($req);
        return $res;
    }

    public function toHeaderStrings($associativeArray)
    {

        $keys = array_keys($associativeArray);
        $values = array_values($associativeArray);


        $func = function($key, $value)
        {
            return  $key . ": " . $value;
        };
        return array_map($func, $keys, $values);
    }

}
