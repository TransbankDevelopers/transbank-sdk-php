<?php
namespace Transbank\Onepay;

class HttpClient {

    function post($url, $path, $data_to_send, $options = array('headers' => 0, 'transport' => 'https', 'port' => 443, 'proxy' => null)) {
        $transport = '';
        $port = 80;
        if (!empty($options['transport'])) $transport = $options['transport'];
        if (!empty($options['port'])) $port = $options['port'];

        $remote = $url  .  $path;
        $http_options = array(
            'method' => 'POST',
            'header' => "Content-type: application/json",
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
    }
}
