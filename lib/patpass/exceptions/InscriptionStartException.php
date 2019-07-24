<?php

/**
 * Class InscriptionStartException
 *
 * @category
 * @package Transbank\PatPass\Commerce\Cxceptions
 *
 */


namespace Transbank\PatPass\Exceptions;


class InscriptionStartException extends TransbankException
{
    public function __construc($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construc($message, $code, $previous);
    }

}