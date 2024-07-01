<?php

namespace Transbank\Webpay\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class TransactionCreateResponse
{
    public string $token;

    public function __construct(array $json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
