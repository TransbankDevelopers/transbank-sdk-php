<?php

/**
 * Class MallTransactionInstallmentsException.
 *
 * @category
 */

namespace Transbank\TransaccionCompleta\Exceptions;

class MallTransactionInstallmentsException extends MallTransactionCompletaException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
