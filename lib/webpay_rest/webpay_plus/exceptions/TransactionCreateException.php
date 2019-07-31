<?php
namespace Transbank\Webpay\Exceptions;
/**
 * class TransactionCreateException
 * Raised when giving invalid params to a TransactionCreateRequest
 *
 * @package Transbank
 *
 *
 */

class TransactionCreateException extends TransbankException {
    const DEFAULT_MESSAGE = 'Transaction could not be created. Please verify given parameters';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
