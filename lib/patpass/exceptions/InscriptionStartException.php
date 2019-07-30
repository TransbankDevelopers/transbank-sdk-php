<?php

/**
 * Class InscriptionStartException
 *
 * @category
 * @package Transbank\PatPass\Commerce\Cxceptions
 *
 */


namespace Transbank\PatPass\Exceptions;
use Transbank\Webpay\Exceptions\WebpayException;
class InscriptionStartException extends WebpayException
{
    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
