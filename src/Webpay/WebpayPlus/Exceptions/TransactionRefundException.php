<?php

namespace Transbank\Webpay\WebpayPlus\Exceptions;

use Transbank\Webpay\Exceptions\WebpayRequestException;

class TransactionRefundException extends WebpayRequestException
{
    protected static $defaultMessage = 'The transaction could not be refunded. ';
}
