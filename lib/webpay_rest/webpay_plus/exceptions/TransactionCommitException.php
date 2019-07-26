<?php


namespace Transbank\Webpay\WebpayPlus\Exceptions;


class TransactionCommitException extends WebpayException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
