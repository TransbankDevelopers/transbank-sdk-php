<?php

namespace Transbank\Patpass\PatpassByWebpay\Exceptions;

use Transbank\Patpass\Exceptions\PatpassException;

class TransactionCreateException extends PatpassException
{
    const DEFAULT_MESSAGE = 'Transaction could not be created. Please verify given parameters';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
