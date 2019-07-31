<?php

/**
 * Class InscriptionStartResponse
 *
 * @category
 * @package Transbank\Patpass\PatpassComercio
 *
 */


namespace Transbank\Patpass\PatpassComercio;


class InscriptionStartResponse
{
    public $token;
    public $url;

    /**
     * InscriptionStartResponse constructor.
     * @param $token
     */
    public function __construct($json)
    {
        $token = $json["token"];
        $url = $json["url"];
        $this->setToken($token);
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
     *
     * @return InscriptionStartResponse
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
     *
     * @return InscriptionStartResponse
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }




}
