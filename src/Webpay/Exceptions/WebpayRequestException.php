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
    protected static string $defaultMessage = 'An error has happened on the request';

    /**
     * @var ?string
     */
    protected ?string $transbankErrorMessage;
    /**
     * @var ?int
     */
    protected $httpCode;

    /**
     * @var mixed
     */
    protected $response;

    /**
     * @var ?TransbankApiRequest
     */
    protected ?TransbankApiRequest $failedRequest;

    /**
     * WebpayRequestException constructor.
     *
     * @param string                $message
     * @param ?string               $tbkErrorMessage
     * @param ?int                  $httpCode
     * @param ?TransbankApiRequest  $failedRequest
     * @param ?\Exception           $previous
     */
    public function __construct(
        string $message,
        ?string $tbkErrorMessage = null,
        ?int $httpCode = null,
        ?TransbankApiRequest $failedRequest = null,
        ?\Exception $previous = null
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
     * @return ?string
     */
    public function getTransbankErrorMessage(): ?string
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
     * @return ?TransbankApiRequest
     */
    public function getFailedRequest(): ?TransbankApiRequest
    {
        return $this->failedRequest;
    }

    /**
     * @param string    $message
     * @param ?string   $tbkErrorMessage
     * @param ?int      $httpCode
     *
     * @return string
     */
    protected function getExceptionMessage(
        string $message,
        ?string $tbkErrorMessage,
        ?int $httpCode
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
