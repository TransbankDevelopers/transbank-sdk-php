<?php

namespace Transbank\Webpay\Modal\Responses;

class TransactionCreateResponse
{
    /**
     * @var string|null
     */
    public $token;

    public function __construct(array $json)
    {
        $this->token = $json['token'];
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }
}
