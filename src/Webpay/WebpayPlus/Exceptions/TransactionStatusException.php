<?php

namespace Transbank\Webpay\WebpayPlus\Exceptions;

use Transbank\Webpay\Exceptions\WebpayRequestException;

class TransactionStatusException extends WebpayRequestException
{
    protected static string $defaultMessage = 'Transaction Status could not be confirmed. Please verify given parameters';
}
