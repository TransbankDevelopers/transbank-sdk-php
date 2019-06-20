<?php
namespace Transbank\Utils;

class HttpClient {

    function post($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null)) {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data_to_send,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Tbk-Api-Key-Id: " . $options['headers']['Tbk-Api-Key-Id'],
                "Tbk-Api-Key-Secret: " . $options['headers']['Tbk-Api-Key-Secret'],
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

}
