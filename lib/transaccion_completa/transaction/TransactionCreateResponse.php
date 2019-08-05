<?php

/**
 * Class TransactionCreateResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


class TransactionCreateResponse
{
    public $token;

    public function __construct($json)
    {
        $token = isset($json["token"]) ? $json["token"] : null;
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
    }


}
