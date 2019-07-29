<?php

namespace Transbank\Webpay\Exceptions;

/**
 * class WebpayException
 *
 *   basic class for  Webpay related exceptions
 *
 * @package Transbank
 *
 */
class WebpayException extends \Exception
{

    const DEFAULT_MESSAGE = 'An error has happened, verify given parameters and try again.';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
