<?php

/**
 * Class InscriptionStartException
 *
 * @category
 * @package Transbank\PatPass\Commerce\Cxceptions
 *
 */


namespace Transbank\Patpass\PatpassComercio\Exceptions;

use Transbank\Patpass\Exceptions\PatpassException;

class InscriptionStartException extends PatpassException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
