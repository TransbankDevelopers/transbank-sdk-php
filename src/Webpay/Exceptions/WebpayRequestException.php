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
        TransbankApiRequest $failedRequest = null
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
     *
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
     * @param TransbankApiRequest|null $failedRequestCapturedData
     *
     * @return string
     */
    protected function getExceptionMessage(
        $message,
        $tbkErrorMessage,
        $httpCode,
        TransbankApiRequest $failedRequestCapturedData = null
    ) {
        if (!$tbkErrorMessage) {
            $theMessage = $message;
        } else {
            $theMessage = 'API Response: "'.$tbkErrorMessage.'" ['.$httpCode.'] - '.static::$defaultMessage;
        }

        if ($possibleCause = $this->getPossibleCause($httpCode, $tbkErrorMessage, $failedRequestCapturedData)) {
            return $theMessage.' - '.$possibleCause;
        }

        return $theMessage;
    }

    /**
     * @param $httpCode
     * @param $tbkErrorMessage
     * @param TransbankApiRequest|null $failedRequestCapturedData
     *
     * @return null
     */
    protected function getPossibleCause($httpCode, $tbkErrorMessage, TransbankApiRequest $failedRequestCapturedData = null)
    {
        return null;
    }
}
