<?php

/**
 * Class TransactionCommitException.
 *
 * @category
 */

namespace Transbank\TransaccionCompleta\Exceptions;

class TransactionCommitException extends TransaccionCompletaException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
