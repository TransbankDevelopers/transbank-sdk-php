<?php

namespace Transbank\Sdk\Services;

use DateTime;

class FullTransaction
{
    public function create(
        string $buyOrder,
        string $sessionId,
        int|float $amount,
        int|string $ccv,
        int|string $cardNumber,
        string|DateTime $expiration,
        array $options = []
    ) {

    }

    public function installments(string $token, int $installments, array $options = [])
    {

    }

    public function commit(
        string $token,
        int $queryInstallmentId,
        int $deferredPeriodIndex,
        bool $gracePeriod,
        array $options = []
    ) {

    }

    public function status(string $token, array $options = [])
    {

    }

    public function refund(string $token, int|float $amount, array $options = [])
    {

    }

    public function capture(string $token, string $buyOrder, int $authorizationCode, int|float $captureAmount, array $options = [])
    {

    }
}
