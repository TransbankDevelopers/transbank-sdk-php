<?php

/**
 * Class InscriptionStartResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta\inscription
 *
 */


namespace Transbank\TransaccionCompleta;

use Transbank\TransaccionCompleta;

class InscriptionStartResponse
{
    public $token;
    public $url;

    public function __construct($json)
    {
        $token = $json["token"];
        $this->setToken($token);
        $url = $json["url"];
        $this->setUrl($url);
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

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }


}
