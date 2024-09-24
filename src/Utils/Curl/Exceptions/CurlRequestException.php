<?php

namespace Transbank\Utils\Curl\Exceptions;

class CurlRequestException extends \Exception
{
    const DEFAULT_MESSAGE = 'An error happened on the request';

    /**
     * RequestException constructor.
     *
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = self::DEFAULT_MESSAGE, int $errorCode = 0, \Throwable|null $previous = null)
    {
        parent::__construct($message, $errorCode, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [error code {$this->code}]: {$this->message} in {$this->file} on line {$this->line}\n";
    }
}
