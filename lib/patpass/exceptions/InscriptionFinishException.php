<?php

/**
 * Class InscriptionFinishException
 *
 * @category
 * @package Transbank\PatPass\Exceptions
 *
 */


namespace Transbank\PatPass\Exceptions;
use Transbank\Webpay\Exceptions\WebpayException;

class InscriptionFinishException extends WebpayException
{

    public function __construc($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construc($message, $code, $previous);
    }
}
