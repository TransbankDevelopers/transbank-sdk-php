<?php
namespace Transbank\Onepay\Exceptions;
/**
 *
 * class RefundCreateException
 * Model object for raising exceptions when a Refund::create transaction fails.
 *
 * @package Transbank\Onepay\Exceptions
 */
class RefundCreateException extends TransbankException {

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
