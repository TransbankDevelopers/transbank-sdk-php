<?php

/**
 * Class InscriptionFinishException
 *
 * @category
 * @package Transbank\TransaccionCompleta\TransaccionCompletaException
 *
 */


namespace Transbank\TransaccionCompleta\Exceptions;

use Transbank\TransaccionCompleta\Exceptions\TransaccionCompletaException;


class InscriptionFinishException extends TransaccionCompletaException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
