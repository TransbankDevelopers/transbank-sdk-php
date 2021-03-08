<?php
namespace Transbank\Webpay\WebpayPlus\Exceptions;

use Transbank\Webpay\Exceptions\WebpayException;
use Transbank\Webpay\Exceptions\WebpayRequestException;

/**
 * class TransactionCreateException
 * Raised when giving invalid params to a TransactionCreateRequest
 *
 * @package Transbank
 *
 *
 */

class TransactionCreateException extends WebpayRequestException
{
    const DEFAULT_MESSAGE = 'Transaction could not be created. Please verify given parameters';
}
