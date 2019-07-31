<?php
namespace Transbank\Patpass\PatpassComercio\Exceptions;

use Transbank\Patpass\Exceptions\PatpassException;

class InscriptionStatusException extends PatpassException {
    const DEFAULT_MESSAGE = 'Could not get Transaction status. Please verify given parameters';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
