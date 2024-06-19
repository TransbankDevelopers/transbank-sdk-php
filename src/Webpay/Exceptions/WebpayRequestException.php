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
     * @param string|null              $tbkErrorMessage
     * @param int|null                 $httpCode
     * @param TransbankApiRequest|null $failedRequest
     * @param \Exception|null          $previous
     */
    public function __construct(
        string $message,
        ?string $tbkErrorMessage = null,
        ?int $httpCode = null,
        ?TransbankApiRequest $failedRequest = null,
        ?\Exception $previous = null
    ) {
        $theMessage = isset($tbkErrorMessage) ? $tbkErrorMessage : $message;
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
        return new static($exception->getMessage(), $exception->getTransbankErrorMessage(), $exception->getHttpCode(),
            $exception->getFailedRequest(), $exception);
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
    public function getFailedRequest(): ?TransbankApiRequest
    {
        return $this->failedRequest;
    }

    /**
     * @param string $message
     * @param string|null $tbkErrorMessage
     * @param int|null $httpCode
     *
     * @return string
     */
    protected function getExceptionMessage(
        string $message,
        ?string $tbkErrorMessage,
        ?int $httpCode
    ): string {

        if (!$tbkErrorMessage) {
            $theMessage = $message;
        } else {
            $theMessage = 'API Response: "'.$tbkErrorMessage.'" ['.$httpCode.'] - '.static::$defaultMessage;
        }

        return $theMessage;
    }

}
