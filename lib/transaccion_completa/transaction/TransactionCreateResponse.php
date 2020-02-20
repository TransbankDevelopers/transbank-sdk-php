<?php

/**
 * Class TransactionCreateResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


use Transbank\Utils\Utils;

class TransactionCreateResponse
{
    public $token;

    public function __construct($json)
    {
        $token =  Utils::returnValueIfExists($json, "token");
        $this->setToken($token);
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }


}
