<?php

namespace Transbank\Patpass\PatpassByWebpay\Exceptions;

use Transbank\Patpass\Exceptions\PatpassException;

class TransactionCommitException extends PatpassException
{
    const DEFAULT_MESSAGE = 'Transaction could not be committed. Please verify given parameters';
}
