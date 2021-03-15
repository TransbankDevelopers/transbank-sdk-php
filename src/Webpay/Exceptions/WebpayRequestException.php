<?php

namespace Transbank\Webpay\Exceptions;

class WebpayRequestException extends WebpayException
{
    const DEFAULT_MESSAGE = 'An error has happened on the request';
    
    /**
     * @var mixed
     */
    protected $transbankErrorMessage;
    /**
     * @var int
     */
    protected $httpCode;
    
    protected $response;
    
    protected $failedRequest;
    
    /**
     * WebpayRequestException constructor.
     *
     * @param string $message
     * @param mixed|string $tbkErrorMessage
     * @param int $httpCode
     * @param FailedRequestCapturedData|null $failedRequest
     */
    public function __construct($message, $tbkErrorMessage, $httpCode, FailedRequestCapturedData $failedRequest = null)
    {
        $theMessage =  $message;
        if ($failedRequest !== null) {
            $theMessage = 'API Response: ' . $tbkErrorMessage . " | \n" .
                ' Request: ' . $failedRequest->getMethod() . ' ' .
                $failedRequest->getEndpoint() . ' | ' .  print_r($failedRequest->getPayload(), true);
        }
        
        $this->message = $theMessage;
        $this->transbankErrorMessage = $tbkErrorMessage;
        $this->httpCode = $httpCode;
        $this->failedRequest = $failedRequest;
    
        parent::__construct($theMessage, $httpCode);
    }
    
    public function getTransbankErrorMessage()
    {
        return $this->transbankErrorMessage;
    }
    
    public static function raise(WebpayRequestException $exception)
    {
        return new static($exception->getMessage(), $exception->getTransbankErrorMessage(), $exception->getHttpCode());
    }
    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }
    /**
     * @return FailedRequestCapturedData|null
     */
    public function getFailedRequest()
    {
        return $this->failedRequest;
    }
}
