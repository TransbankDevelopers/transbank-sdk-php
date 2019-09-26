<?php

/**
 * Class InscriptionStartException
 *
 * @category
 * @package Transbank\Patpass\PatpassComercio\Exceptions
 *
 */

namespace Transbank\Patpass\PatpassComercio\Exceptions;

use Transbank\PatPass\Exceptions\PatpassException;

class InscriptionStartException extends PatpassException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
