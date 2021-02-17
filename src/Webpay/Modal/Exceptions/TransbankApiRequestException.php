<?php

namespace Transbank\Webpay\Modal\Exceptions;

class TransbankApiRequestException extends \Exception
{
    const DEFAULT_MESSAGE = 'An error has ocurred.';

    protected $transbankError = null;

    public function __construct($message = self::DEFAULT_MESSAGE, $transbankError = null, $code = 0, $previous = null)
    {
        $this->transbankError = $transbankError;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed|null
     */
    public function getTransbankError()
    {
        return $this->transbankError;
    }
}
