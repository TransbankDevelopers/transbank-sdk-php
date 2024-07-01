<?php

namespace Transbank\Utils;

/**
 * Class TransactionStatusEnum
 *
 * @package Transbank\Utils
 */
class TransactionStatusEnum
{
    public const STATUS_AUTHORIZED = 'AUTHORIZED';
    public const STATUS_NULLIFIED = 'NULLIFIED';
    public const STATUS_REVERSED = 'REVERSED';
    public const STATUS_PARTIALLY_NULLIFIED = 'PARTIALLY_NULLIFIED';
    public const STATUS_CAPTURED = 'CAPTURED';
    public const STATUS_FAILED = 'FAILED';
}
