<?php


namespace Transbank\Webpay\exceptions;


class TransactionCommitException extends TransbankException
{
    public function __construc($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::_construct($message, $code, $previous);
    }

}
