<?php

/**
 * Class TransactionRefundException
 *
 * @category
 * @package Transbank\TransaccionCompleta\Exceptions
 *
 */


namespace Transbank\TransaccionCompleta\Exceptions;


class TransactionRefundException extends TransaccionCompletaException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
