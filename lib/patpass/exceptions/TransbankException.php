<?php

/**
 * Class TransbankException
 *
 * @category
 * @package Transbank\PatPass\Exceptions
 *
 */


namespace Transbank\PatPass\Exceptions;


class TransbankException
{
    const DEFAULT_MESSAGE = 'An error has happened, verify given parameters and try again.';

    public function __construc($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::_construct($message, $code, $previous);
    }

}
