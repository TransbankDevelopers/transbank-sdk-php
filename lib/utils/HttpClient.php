<?php
namespace Transbank\Utils;

class HttpClient {

 /*   function post($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null)) {
        $transport = '';
        $port = 80;
        if (!empty($options['transport'])) $transport = $options['transport'];
        if (!empty($options['port'])) $port = $options['port'];

        $remote = $url  .  $path;


        $basicHeaders = ["Content-Type" => 'application/json', 'transport' => 'https', 'port' => 443, 'proxy' => null];

        $optionsHeaders = $options['headers'] ? $options['headers'] : [];

        $headers = array_merge($basicHeaders, $optionsHeaders);

        $http_options = array(
            'method' => 'POST',
            'headers' => $this->toHeaderString($headers),
            'content' => $data_to_send
        );

        if(isset($options['proxy']) && $options['proxy'] != null) {
            $http_options['proxy'] = $options['proxy'];
        }

        $ssl_options = array(
            'verify_host' => true
        );

        $context = stream_context_create(array(
            'http' => $http_options,
            'ssl' => $ssl_options
        ));

        echo "OPTIONS";
        var_dump($http_options);
        echo "\n";
        echo "CONTEXT";
        var_dump($context);
        echo "\n";
        echo "REMOTE";
        var_dump($remote);

        echo "SSL";
        var_dump($ssl_options);


        $fp = fopen($remote, 'r', false, $context);

        $response_metadata = stream_get_meta_data($fp);
        if (1 != preg_match("/^HTTP\/[0-9\.]* ([0-9]{3}) ([^\r\n]*)/", $response_metadata['wrapper_data'][0], $matches)) {
            trigger_error('httpPost: invalid HTTP reply.');
            fclose($fp);
            return null;
        }

        if ($matches[1] != '200') {
            trigger_error('httpPost: HTTP error: ' . $matches[1] . ' ' . $matches[2]);
            fclose($fp);
            return null;
        }

        switch (intval($matches[1])) {
            case 200: // OK
            case 304: // Not modified
                break;
            case 301: // Moved permanently
            case 302: // Moved temporarily
            case 307: // Moved temporarily
                break;
            default:
                trigger_error('httpPost: HTTP error: ' . $matches[1] . ' ' . $matches[2]);
                return null;
        }

        $response_body = stream_get_contents($fp);

        fclose($fp);

        return $response_body;
    }*/


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
            CURLOPT_POSTFIELDS => json_encode($data_to_send),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Tbk-Api-Key-Id: " . $options['Tbk-Api-Key-Id'],
                "Tbk-Api-Key-Secret: " . $options['Tbk-Api-Key-Secret'],
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }


    }




    public function toHeaderString($associativeArray)
    {

        $keys = array_keys($associativeArray);
        $values = array_values($associativeArray);


        $func = function($key, $value)
        {
            return "'" . $key . ": " . $value . "'";
        };


        $stringsArray = array_map($func, $keys, $values);
        return join("; ", $stringsArray) . ';';
    }


}
