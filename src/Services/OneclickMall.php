<?php

namespace Transbank\Sdk\Services;

class OneclickMall
{
    public function start(string $username, string $email, string $responseUrl, array $options = []): Transactions\Transaction
    {

    }

    public function finish(string $token, array $options = [])
    {

    }

    public function delete(string $tbkUser, string $username, array $options = [])
    {

    }

    public function authorize(string $tbkUser, string $username, string $parentBuyOrder, array $details, array $options = [])
    {

    }

    public function status(string $buyOrder, array $options = [])
    {

    }

    public function refund(string $childCommerceCode, string $childBuyOrder, int|float $amount, array $options = [])
    {

    }

    public function capture(string $commerceCode, string $buyOrder, int $authorizationCode, int|float $amount, array $options = [])
    {

    }
}
