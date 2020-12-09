<?php


namespace Transbank\Webpay\WebpayPlus\Exceptions;

use Transbank\Webpay\Exceptions\WebpayException;

/**
 * Class TransactionStatusException
 *
 * @package Transbank\Webpay\exceptions
 */
class TransactionStatusException extends WebpayException
{
    const DEFAULT_MESSAGE = 'Transaction Status could not be confirmed. Please verify given parameters';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
