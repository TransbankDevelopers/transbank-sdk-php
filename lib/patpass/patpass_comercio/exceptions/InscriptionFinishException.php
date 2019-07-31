<?php

/**
 * Class InscriptionFinishException
 *
 * @category
 * @package Transbank\PatPass\Exceptions
 *
 */


namespace Transbank\Patpass\PatpassComercio\Exceptions;

use Transbank\Patpass\Exceptions\PatpassException;

class InscriptionFinishException extends PatpassException
{

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
