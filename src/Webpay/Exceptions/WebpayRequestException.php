<?php

namespace Transbank\Webpay\Exceptions;

/**
 * Class WebpayRequestException.
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
     * @var TransbankApiRequest|null
     */
    protected $failedRequest;

    /**
     * WebpayRequestException constructor.
     *
     * @param string                   $message
     * @param mixed|string             $tbkErrorMessage
     * @param int                      $httpCode
     * @param TransbankApiRequest|null $failedRequest
     */
    public function __construct(
        $message,
        $tbkErrorMessage = null,
        $httpCode = null,
        TransbankApiRequest $failedRequest = null,
        \Exception $previous = null
    ) {
        $theMessage = isset($tbkErrorMessage) ? $tbkErrorMessage : $message;
        if ($failedRequest !== null) {
            $theMessage = $this->getExceptionMessage($message, $tbkErrorMessage, $httpCode);
        }

        $this->message = $theMessage;
        $this->transbankErrorMessage = $tbkErrorMessage;
        $this->httpCode = $httpCode;
        $this->failedRequest = $failedRequest;

        parent::__construct($theMessage, $httpCode, $previous);
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
     *
     * @return static
     */
    public static function raise(WebpayRequestException $exception)
    {
        return new static($exception->getMessage(), $exception->getTransbankErrorMessage(), $exception->getHttpCode(),
            $exception->getFailedRequest(), $exception);
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return TransbankApiRequest|null
     */
    public function getFailedRequest()
    {
        return $this->failedRequest;
    }

    /**
     * @param $message
     * @param $tbkErrorMessage
     * @param $httpCode
     *
     * @return string
     */
    protected function getExceptionMessage(
        $message,
        $tbkErrorMessage,
        $httpCode
    ) {
        if (!$tbkErrorMessage) {
            $theMessage = $message;
        } else {
            $theMessage = 'API Response: "'.$tbkErrorMessage.'" ['.$httpCode.'] - '.static::$defaultMessage;
        }

        return $theMessage;
    }

}
