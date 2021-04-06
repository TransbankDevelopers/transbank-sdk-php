<?php

/**
 * Class MallTransactionCommitException.
 *
 * @category
 */

namespace Transbank\TransaccionCompleta\Exceptions;

class MallTransactionCommitException extends MallTransactionCompletaException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
