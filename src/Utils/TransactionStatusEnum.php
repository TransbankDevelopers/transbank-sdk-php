<?php

namespace Transbank\Utils;

class TransactionStatusEnum
{
    const STATUS_AUTHORIZED = 'AUTHORIZED';
    const STATUS_NULLIFIED = 'NULLIFIED';
    const STATUS_REVERSED = 'REVERSED';
    const STATUS_PARTIALLY_NULLIFIED = 'PARTIALLY_NULLIFIED';
    const STATUS_CAPTURED = 'PARTIALLY_NULLIFIED';
    const STATUS_FAILED = 'FAILED';
}
