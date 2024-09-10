<?php

namespace Transbank\Webpay\Exceptions;

use Transbank\Utils\TransbankApiRequest;

/**
 * Class WebpayRequestException.
 */
class WebpayRequestException extends WebpayException
{
    /**
     * @var string
     */
    protected static string $defaultMessage = 'An error has happened on the request';

    /**
     * @var string|null
     */
    protected string|null $transbankErrorMessage;
    /**
     * @var int|null
     */
    protected $httpCode;

    /**
     * @var mixed
     */
    protected $response;

    /**
     * @var TransbankApiRequest|null
     */
    protected TransbankApiRequest|null $failedRequest;

    /**
     * WebpayRequestException constructor.
     *
     * @param string                    $message
     * @param string|null               $tbkErrorMessage
     * @param int|null                  $httpCode
     * @param TransbankApiRequest|null  $failedRequest
     * @param \Exception|null           $previous
     */
    public function __construct(
        string $message,
        string|null $tbkErrorMessage = null,
        int|null $httpCode = null,
        TransbankApiRequest|null $failedRequest = null,
        \Exception|null $previous = null
    ) {
        $theMessage = $tbkErrorMessage ?? $message;

        if ($failedRequest !== null) {
            $theMessage = $this->getExceptionMessage($message, $tbkErrorMessage, $httpCode);
        }

        $this->message = $theMessage;
        $this->transbankErrorMessage = $tbkErrorMessage;
        $this->httpCode = $httpCode;
        $this->failedRequest = $failedRequest;

        parent::__construct($theMessage, $httpCode ?? 0, $previous);
    }

    /**
     * @return string|null
     */
    public function getTransbankErrorMessage(): string|null
    {
        return $this->transbankErrorMessage;
    }

    /**
     * @param WebpayRequestException $exception
     *
     * @return static
     */
    public static function raise(WebpayRequestException $exception): self
    {
        return new static(
            $exception->getMessage(),
            $exception->getTransbankErrorMessage(),
            $exception->getHttpCode(),
            $exception->getFailedRequest(),
            $exception
        );
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode ?? 0;
    }

    /**
     * @return TransbankApiRequest|null
     */
    public function getFailedRequest(): TransbankApiRequest|null
    {
        return $this->failedRequest;
    }

    /**
     * @param string    $message
     * @param string|null   $tbkErrorMessage
     * @param int|null      $httpCode
     *
     * @return string
     */
    protected function getExceptionMessage(
        string $message,
        string|null $tbkErrorMessage,
        int|null $httpCode
    ): string {
        if (!$tbkErrorMessage) {
            return $message;
        }

        return sprintf(
            'API Response: "%s" [%d] - %s',
            $tbkErrorMessage,
            $httpCode ?? 0,
            static::$defaultMessage
        );
    }
}
