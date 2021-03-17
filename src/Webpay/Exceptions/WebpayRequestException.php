<?php

namespace Transbank\Webpay\Exceptions;

use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;

/**
 * Class WebpayRequestException
 *
 * @package Transbank\Webpay\Exceptions
 */
class WebpayRequestException extends WebpayException
{
    /**
     * @var string
     */
    protected static $defaultMessage = 'An error has happened on the request';
    
    /**
     * @var mixed
     */
    protected $transbankErrorMessage;
    /**
     * @var int
     */
    protected $httpCode;
    
    /**
     * @var
     */
    protected $response;
    
    /**
     * @var FailedRequestCapturedData|null
     */
    protected $failedRequest;
    
    /**
     * WebpayRequestException constructor.
     *
     * @param string $message
     * @param mixed|string $tbkErrorMessage
     * @param int $httpCode
     * @param FailedRequestCapturedData|null $failedRequest
     */
    public function __construct(
        $message,
        $tbkErrorMessage = null,
        $httpCode = null,
        FailedRequestCapturedData $failedRequest = null
    ) {
        $theMessage = isset($tbkErrorMessage) ? $tbkErrorMessage : $message;
        if ($failedRequest !== null) {
            $theMessage = $this->getExceptionMessage($message, $tbkErrorMessage, $httpCode, $failedRequest);
        }
        
        $this->message = $theMessage;
        $this->transbankErrorMessage = $tbkErrorMessage;
        $this->httpCode = $httpCode;
        $this->failedRequest = $failedRequest;
        
        parent::__construct($theMessage, $httpCode);
    }
    
    /**
     * @return mixed
     */
    public function getTransbankErrorMessage()
    {
        return $this->transbankErrorMessage;
    }
    
    /**
     * @param WebpayRequestException $exception
     * @return static
     */
    public static function raise(WebpayRequestException $exception)
    {
        return new static($exception->getMessage(), $exception->getTransbankErrorMessage(), $exception->getHttpCode(),
            $exception->getFailedRequest());
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
    
    /**
     * @param $message
     * @param $tbkErrorMessage
     * @param $httpCode
     * @param FailedRequestCapturedData|null $failedRequestCapturedData
     * @return string
     */
    protected function getExceptionMessage(
        $message,
        $tbkErrorMessage,
        $httpCode,
        FailedRequestCapturedData $failedRequestCapturedData = null
    ) {
        if (!$tbkErrorMessage) {
            $theMessage = $message;
        } else {
            $theMessage = 'API Response: "' . $tbkErrorMessage . '" [' . $httpCode . '] - ' . static::$defaultMessage;
        }
        
        if ($possibleCause = $this->getPossibleCause($httpCode, $tbkErrorMessage, $failedRequestCapturedData)) {
            return $theMessage . ' - ' . $possibleCause;
        }
        
        return $theMessage;
    }
    
    /**
     * @param $httpCode
     * @param $tbkErrorMessage
     * @param FailedRequestCapturedData|null $failedRequestCapturedData
     * @return null
     */
    protected function getPossibleCause($httpCode, $tbkErrorMessage, FailedRequestCapturedData $failedRequestCapturedData = null)
    {
        return null;
    }
}
