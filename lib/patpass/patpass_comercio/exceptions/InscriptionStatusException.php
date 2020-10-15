<?php

/**
 * Class InscriptionStatusException
 *
 * @category
 * @package Transbank\Patpass\PatpassComercio\Exceptions
 *
 */


namespace Transbank\Patpass\PatpassComercio\Exceptions;

use Transbank\Patpass\Exceptions\PatpassException;

class InscriptionStatusException extends PatpassException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
