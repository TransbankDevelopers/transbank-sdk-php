<?php
namespace Transbank\Onepay\Exceptions;
/**
 * class TransactionCommitException
 * Raised when there is an error when commiting the transaction
 * 
 * @package Transbank
 * 
 * 
 */

 class TransactionCommitException extends TransbankException {

    const DEFAULT_MESSAGE = 'Error when commiting Transaction. Please verify the given parameters.';

    public function __construc($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::_construct($message, $code, $previous);
    }
 }
