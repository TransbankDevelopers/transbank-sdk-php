<?php

namespace Transbank\Patpass\PatpassByWebpay\Exceptions;

use Transbank\Patpass\Exceptions\PatpassException;

class TransactionStatusException extends PatpassException
{
    const DEFAULT_MESSAGE = 'Could not get Transaction status. Please verify given parameters';
}
