<?php


namespace Transbank\Webpay\Exceptions;


/**
 * Class TransactionStatusException
 *
 * @package Transbank\Webpay\exceptions
 */
class TransactionStatusException extends TransbankException {
    const DEFAULT_MESSAGE = 'Transaction Status could not be confirmed. Please verify given parameters';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
