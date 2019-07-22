<?php

namespace Transbank\Webpay\PatPassByWebpay\Exceptions;

use Transbank\Webpay\Exceptions\TransbankException;

class TransactionCreateException extends TransbankException {
    const DEFAULT_MESSAGE = 'Transaction could not be created. Please verify given parameters';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
