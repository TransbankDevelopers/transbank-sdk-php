<?php
namespace Transbank\Onepay\Exceptions;
/**
 * class SignException
 * Raised when the signature is invalid.
 * 
 * @package Transbank
 * 
 * 
 */

 class SignException extends TransbankException { 
    const DEFAULT_MESSAGE = 'Signature does not match';

    public function __construc($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::_construct($message, $code, $previous);
    }
 }
