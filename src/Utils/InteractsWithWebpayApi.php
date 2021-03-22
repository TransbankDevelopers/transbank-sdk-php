<?php

namespace Transbank\Utils;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Transbank\Webpay\Exceptions\TransbankApiRequest;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;

/**
 * Trait InteractsWithWebpayApi.
 */
trait InteractsWithWebpayApi
{
    
    /**
     * @var ResponseInterface|null
     */
    protected $lastResponse = null;

    /**
     * @var TransbankApiRequest|null
     */
    protected $lastRequest = null;
    /**
     * @var HttpClient
     */
    protected $httpClient;
    /**
     * @var Options
     */
    protected $options;
    /**
     * Transaction constructor.
     *
     * @param Options $options
     * @param HttpClient $httpClient
     */
    public function __construct(
        Options $options = null,
        HttpClient $httpClient = null
    ) {
        $this->setOptions($options !== null ? $options : $this->getDefaultOptions());
        $this->setHttpClient($httpClient !== null ? $httpClient : new HttpClient());
    }
    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }
    /**
     * @param Options $options
     */
    public function setOptions(Options $options)
    {
        $this->options = $options;
    }
    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }
    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
    
    /**
     * @param $method
     * @param $endpoint
     * @param $payload
     * @return mixed
     * @throws GuzzleException
     * @throws WebpayRequestException
     */
    protected function request(
        $method,
        $endpoint,
        $payload
    ) {
        $headers = $this->getOptions()->getHeaders();
        
        $client = $this->httpClient;
        if ($client == null) {
            $client = new HttpClient();
        }

        $baseUrl = $this->getBaseUrl();
        $request = new TransbankApiRequest($method, $baseUrl, $endpoint, $payload, $headers);

        $this->setLastRequest($request);
        $response = $client->perform($method, $baseUrl . $endpoint, $payload, ['headers' => $headers]);
    
        $this->setLastResponse($response);
        $httpCode = $response->getStatusCode();

        if (!in_array($httpCode, [200, 204])) {
            $reason = $response->getReasonPhrase();
            $message = "Could not obtain a response from Transbank API: $reason (HTTP code $httpCode)";
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
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param ResponseInterface|null $lastResponse
     */
    public function setLastResponse($lastResponse)
    {
        $this->lastResponse = $lastResponse;
    }

    /**
     * @return TransbankApiRequest|null
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @param TransbankApiRequest|null $lastRequest
     */
    public function setLastRequest($lastRequest)
    {
        $this->lastRequest = $lastRequest;
    }
    
    /**
     * @return Options
     */
    protected function getDefaultOptions()
    {
        return Options::forIntegration(Options::DEFAULT_COMMERCE_CODE, Options::DEFAULT_API_KEY);
    }
    
    /**
     *
     * @return mixed|string
     */
    protected function getBaseUrl()
    {
        return $this->getOptions()->getApiBaseUrl();
    }
}
