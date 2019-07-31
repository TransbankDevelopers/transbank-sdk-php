<?php
namespace Transbank\TransaccionCompleta\Exceptions;

use Transbank\TransaccionCompleta\Exceptions;

class InscriptionStartException extends Exceptions
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
