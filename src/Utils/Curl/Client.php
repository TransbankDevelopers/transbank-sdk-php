<?php

namespace Transbank\Utils\Curl;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;


class Client implements ClientInterface
{
    private int $timeout;
    public function __construct(int $timeout)
    {
        $this->timeout = $timeout;
    }
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $curl = curl_init();

        if (!$curl) {
            throw new \RuntimeException('Unable to initialize cURL session.');
        }

        curl_setopt_array($curl, [
            CURLOPT_URL => (string) $request->getUri(),
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
        ]);

        $headers = [];
        foreach ($request->getHeaders() as $name => $value) {
            $headers[] = "$name: $value";
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $body = (string) $request->getBody();
        if (!empty($body)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($curl);
        if ($response === false) {
            throw new \RuntimeException('cURL error: ' . curl_error($curl));
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $rawHeaders = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        curl_close($curl);

        $headers = $this->parseHeaders($rawHeaders);

        return new Response($statusCode, $headers, $body);
    }

    private function parseHeaders(string $rawHeaders): array
    {
        $headers = [];
        $lines = explode("\r\n", $rawHeaders);

        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($name, $value) = explode(': ', $line, 2);
                $headers[$name][] = $value;
            }
        }

        return $headers;
    }
}
