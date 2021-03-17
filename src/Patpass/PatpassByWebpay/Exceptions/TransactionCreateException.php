<?php

namespace Transbank\Patpass\PatpassByWebpay\Exceptions;

use Transbank\Patpass\Exceptions\PatpassException;

class TransactionCreateException extends PatpassException
{
    const DEFAULT_MESSAGE = 'Transaction could not be created. Please verify given parameters';
}
