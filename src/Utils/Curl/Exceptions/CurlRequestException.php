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
    public function __construct(string $message = self::DEFAULT_MESSAGE, \Throwable|null $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
