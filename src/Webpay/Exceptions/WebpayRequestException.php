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
    
    /**
     * WebpayRequestException constructor.
     *
     * @param string $message
     * @param mixed|string $tbkErrorMessage
     * @param int $httpCode
     */
    public function __construct($message, $tbkErrorMessage, $httpCode)
    {
        $this->message = $message;
        $this->transbankErrorMessage = $tbkErrorMessage;
        $this->httpCode = $httpCode;
    
        parent::__construct($message, $httpCode);
    }
}
