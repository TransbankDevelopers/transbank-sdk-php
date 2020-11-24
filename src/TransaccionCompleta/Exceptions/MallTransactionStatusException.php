<?php

/**
 * Class MallTransactionStatusException
 *
 * @category
 * @package Transbank\TransaccionCompleta\Exceptions
 *
 */


namespace Transbank\TransaccionCompleta\Exceptions;

class MallTransactionStatusException extends MallTransactionCompletaException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
