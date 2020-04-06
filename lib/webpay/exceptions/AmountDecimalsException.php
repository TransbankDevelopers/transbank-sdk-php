<?php
namespace Transbank\Webpay\Exceptions;
/**
 * class AmountException
 *   Happens when the amount of something is invalid
 *
 * @package Transbank
 *
 *
 */

 class AmountDecimalsException extends AmountException {

    const DEFAULT_MESSAGE = 'Invalid amount with decimals given.';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
 }
