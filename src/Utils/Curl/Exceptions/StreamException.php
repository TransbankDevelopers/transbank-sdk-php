<?php

namespace Transbank\Utils\Curl\Exceptions;

class StreamException extends \Exception
{
    const DEFAULT_MESSAGE = 'An error happened on the stream';

    /**
     * StreamException constructor.
     *
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = self::DEFAULT_MESSAGE, \Throwable|null $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
