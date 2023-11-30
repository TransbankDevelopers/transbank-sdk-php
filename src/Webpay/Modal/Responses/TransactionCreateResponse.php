<?php

namespace Transbank\Webpay\Modal\Responses;

use Transbank\Utils\Utils;

class TransactionCreateResponse
{
    /**
     * @var string|null
     */
    public $token;

    public function __construct(array $json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }
}
