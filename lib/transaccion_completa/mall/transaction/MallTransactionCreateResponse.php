<?php

/**
 * Class MallTransactionCreateResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


class MallTransactionCreateResponse
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
     * @return MallTransactionCreateResponse
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }




}
