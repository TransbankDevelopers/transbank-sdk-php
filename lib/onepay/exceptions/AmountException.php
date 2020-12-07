<?php
namespace Transbank\Onepay\Exceptions;
/**
 * class AmountException
 *   Happens when the amount of something is invalid
 * 
 * @package Transbank
 * 
 * 
 */

 class AmountException extends TransbankException {

    const DEFAULT_MESSAGE = 'Invalid amount given.';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }



 }
