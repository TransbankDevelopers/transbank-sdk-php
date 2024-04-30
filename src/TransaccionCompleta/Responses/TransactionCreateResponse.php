<?php

namespace Transbank\TransaccionCompleta\Responses;

use Transbank\Utils\Utils;

class TransactionCreateResponse
{
    public $token;

    public function __construct($json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

}
