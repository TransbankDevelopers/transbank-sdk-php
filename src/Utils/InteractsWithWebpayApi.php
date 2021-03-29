<?php

namespace Transbank\Utils;

use GuzzleHttp\Exception\GuzzleException;
use http\Env\Request;
use http\Exception\InvalidArgumentException;
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
     * @var Options
     */
    protected $options;
    /**
     * @var RequestService|null
     */
    protected $requestService;
    /**
     * Transaction constructor.
     *
     * @param Options $options
     * @param RequestService|null $requestService
     */
    public function __construct(
        Options $options = null,
        RequestService $requestService = null
    ) {
        $this->loadOptions($options);
        
        $this->setRequestService($requestService !== null ? $requestService :
            new RequestService());
    }
    
    /**
     * @param $method
     * @param $endpoint
     * @param array $payload
     * @return mixed
     * @throws GuzzleException
     * @throws WebpayRequestException
     */
    public function request($method, $endpoint, $payload = [])
    {
        return $this->getRequestService()->request(
            $method,
            $endpoint,
            $payload,
            $this->getOptions()
        );
    }
    /**
     * @param Options $options
     * @return $this
     */
    public function loadOptions(Options $options = null)
    {
        $defaultOptions = method_exists($this, 'getGlobalOptions') && $this::getGlobalOptions() !== null ?
            $this::getGlobalOptions() : $this->getDefaultOptions();
        if (!$options) {
            $options = $defaultOptions;
        }
        
        if ($options === null) {
            throw new \InvalidArgumentException('No options configuration given');
        }
        
        $this->setOptions($options);
        
        return $this;
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
     * @return RequestService|null
     */
    public function getRequestService()
    {
        return $this->requestService;
    }
    /**
     * @param RequestService|null $requestService
     */
    public function setRequestService($requestService)
    {
        $this->requestService = $requestService;
    }
    
    public static function build(Options $options = null, RequestService $requestService = null)
    {
        return new static($options, $requestService);
    }
    
    /**
     *
     * @return mixed|string
     */
    protected function getBaseUrl()
    {
        return $this->getOptions()->getApiBaseUrl();
    }
    
    public static function configureForIntegration($commerceCode, $apiKey)
    {
        static::setGlobalOptions(Options::forIntegration($commerceCode, $apiKey));
    }
    
    public static function configureForProduction($commerceCode, $apiKey)
    {
        static::setGlobalOptions(Options::forProduction($commerceCode, $apiKey));
    }
}
