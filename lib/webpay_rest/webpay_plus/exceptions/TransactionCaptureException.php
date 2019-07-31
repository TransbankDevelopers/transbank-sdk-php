<?php


namespace Transbank\Webpay\Exceptions;


class TransactionCaptureException extends TransbankException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
