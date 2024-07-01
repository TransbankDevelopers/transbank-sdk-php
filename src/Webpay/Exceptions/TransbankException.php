<?php

namespace Transbank\Webpay\Exceptions;

class TransbankException extends \Exception
{
    const DEFAULT_MESSAGE = 'An error has happened, verify given parameters and try again.';

    /**
     * TransbankException constructor.
     *
     * @param string $message
     * @param int $code
     * @param ?\Throwable $previous
     */
    public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
