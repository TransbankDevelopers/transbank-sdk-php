<?php

namespace Transbank\Webpay\Exceptions;

/**
 * class WebpayException.
 *
 *   basic class for  Webpay related exceptions
 */
class WebpayException extends \Exception
{
    const DEFAULT_MESSAGE = 'An error has happened, verify given parameters and try again.';

    /**
     * WebpayException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = 0, \Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
