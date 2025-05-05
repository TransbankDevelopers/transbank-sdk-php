<?php

namespace Transbank\Webpay\WebpayPlus\Exceptions;

use Transbank\Webpay\Exceptions\WebpayRequestException;

class TransactionCreateException extends WebpayRequestException
{
    protected static string $defaultMessage = 'The transaction could not be created.';
}
