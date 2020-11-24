<?php

/**
 * Class MallTransactionCompletaException
 *
 * @category
 * @package Transbank\TransaccionCompleta\Exceptions
 *
 */


namespace Transbank\TransaccionCompleta\Exceptions;

class MallTransactionCompletaException extends \Exception
{
    const DEFAULT_MESSAGE = 'An error has happened, verify given parameters and try again.';
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
