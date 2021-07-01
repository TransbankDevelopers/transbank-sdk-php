<?php

namespace Transbank\Onepay\Exceptions;

 /**
  * class TransbankException.
  *
  *   basic class for Transbank related exceptions
  */
 class TransbankException extends \Exception
 {
     const DEFAULT_MESSAGE = 'An error has happened, verify given parameters and try again.';

     public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
     {
         parent::__construct($message, $code, $previous);
     }
 }
