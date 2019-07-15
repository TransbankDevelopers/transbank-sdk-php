<?php
namespace Transbank\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class HttpClient {

    function post($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null)) {

        $fullPath = $url . $path;
        $basicHeader = ["Content-Type" => "application/json"];
        $headers = array_merge($basicHeader, $options["headers"]);

        $req = new Request('POST', $fullPath,  $headers, $data_to_send);
        $cl = new Client();

        $res = $cl->send($req);
        return $res;
    }

    function put($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null))
    {

        $curl = curl_init();
        $optionsHeaders = $options["headers"] ? $options["headers"] : [];

        $basicHeader = ["Content-Type" => "application/json"];
        $headers = $this->toHeaderStrings(
            array_merge($basicHeader, $optionsHeaders)
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $data_to_send,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

    function get($url, $path, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null))
    {
        $curl = curl_init();
        $optionsHeaders = $options["headers"] ? $options["headers"] : [];

        $basicHeader = ["Content-Type" => "application/json"];
        $headers = $this->toHeaderStrings(
            array_merge($basicHeader, $optionsHeaders)
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

    public function delete($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null))
    {
        $curl = curl_init();
        $optionsHeaders = $options["headers"] ? $options["headers"] : [];

        $basicHeader = ["Content-Type" => "application/json"];
        $headers = $this->toHeaderStrings(
            array_merge($basicHeader, $optionsHeaders)
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => $data_to_send,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
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
