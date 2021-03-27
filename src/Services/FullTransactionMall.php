<?php

namespace Transbank\Sdk\Services;

use DateTime;

class FullTransactionMall
{
    public function create(
        string $buyOrder,
        string $sessionId,
        int|string $ccv,
        int|string $cardNumber,
        string|DateTime $expiration,
        array $details,
        array $options = []
    ) {

    }

    public function installments(string $commerceCode, string $token, int $installments, array $options = [])
    {

    }

    public function commit(array $details, array $options = [])
    {

    }

    public function status(string $token, array $options = [])
    {

    }

    public function refund(string $commerceCode, string $token, string $buyOrder, int|float|null $amount, array $options = [])
    {

    }

    public function nullify(string $commerceCode, string $token, string $buyOrder, array $options = [])
    {

    }

    public function capture(string $commerceCode, string $token, string $buyOrder, int $authorizationCode, int|float $captureAmount, array $options = [])
    {

    }


}
