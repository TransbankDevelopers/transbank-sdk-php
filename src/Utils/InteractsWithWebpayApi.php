<?php

namespace Transbank\Utils;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Transbank\Webpay\Exceptions\TransbankApiRequest;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;

/**
 * Trait InteractsWithWebpayApi.
 */
trait InteractsWithWebpayApi
{
    /**
     * @var ResponseInterface|null
     */
    protected static $lastResponse = null;

    /**
     * @var TransbankApiRequest|null
     */
    protected static $lastRequest = null;

    /**
     * @param $method
     * @param $endpoint
     * @param $payload
     * @param Options         $options
     * @param HttpClient|null $client
     *
     * @throws GuzzleException|WebpayRequestException
     *
     * @return mixed
     */
    protected static function request(
        $method,
        $endpoint,
        $payload,
        Options $options,
        HttpClient $client = null
    ) {
        $headers = static::getHeaders($options);
        if ($client == null) {
            $client = new HttpClient();
        }

        $baseUrl = static::getBaseUrl($options->getIntegrationType());
        $request = new TransbankApiRequest($method, $baseUrl, $endpoint, $payload, $headers);

        static::setLastRequest($request);
        $response = $client->perform($method, $baseUrl.$endpoint, $payload, ['headers' => $headers]);

        static::setLastResponse($response);
        $httpCode = $response->getStatusCode();

        if (!in_array($httpCode, [200, 204])) {
            $reason = $response->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode)";
            $body = json_decode($response->getBody(), true);
            $tbkErrorMessage = null;
            if (isset($body['error_message'])) {
                $tbkErrorMessage = $body['error_message'];
                $message = "Transbank API REST Error: $tbkErrorMessage | $message";
            }

            throw new WebpayRequestException($message, $tbkErrorMessage, $httpCode, $request);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * @param $integrationEnvironment
     *
     * @return mixed|string
     */
    protected static function getBaseUrl($integrationEnvironment)
    {
        return WebpayPlus::getIntegrationTypeUrl($integrationEnvironment);
    }

    /**
     * @return ResponseInterface|null
     */
    public static function getLastResponse()
    {
        return self::$lastResponse;
    }

    /**
     * @param ResponseInterface|null $lastResponse
     */
    public static function setLastResponse($lastResponse)
    {
        self::$lastResponse = $lastResponse;
    }

    /**
     * @return TransbankApiRequest|null
     */
    public static function getLastRequest()
    {
        return self::$lastRequest;
    }

    /**
     * @param TransbankApiRequest|null $lastRequest
     */
    public static function setLastRequest($lastRequest)
    {
        self::$lastRequest = $lastRequest;
    }

    /**
     * @param Options $options
     *
     * @return array
     */
    public static function getHeaders(Options $options)
    {
        $headers = [
            'Tbk-Api-Key-Id'     => $options->getCommerceCode(),
            'Tbk-Api-Key-Secret' => $options->getApiKey(),
        ];

        return $headers;
    }
}
