<?php

namespace Transbank\Onepay\Exceptions;

 /**
  * class SignException
  * Raised when the signature is invalid.
  */
 class SignException extends TransbankException
 {
     const DEFAULT_MESSAGE = 'Signature does not match';

     public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
     {
         parent::__construct($message, $code, $previous);
     }
 }
