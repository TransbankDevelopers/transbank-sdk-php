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

class InvalidAmountException extends TransbankException {

    const DEFAULT_MESSAGE = 'Invalid amount given.';
    const NOT_NUMERIC_MESSAGE = 'Given amount is not numeric.';
    const HAS_DECIMALS_MESSAGE = 'Given amount has decimals. Webpay only accepts integer amounts. Please remove decimal places.';

    public function __construct($message = self::DEFAULT_MESSAGE, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
