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
    public $urlWebpay;

    /**
     * InscriptionStartResponse constructor.
     * @param $token
     */
    public function __construct($json)
    {
        $token = $json["token"];
        $url = $json["url_webpay"];
        $this->setToken($token);
        $this->setUrlWebpay($url);
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
    public function getUrlWebpay()
    {
        return $this->urlWebpay;
    }

    /**
     * @param mixed $url
     *
     * @return InscriptionStartResponse
     */
    public function setUrlWebpay($url)
    {
        $this->urlWebpay = $url;
        return $this;
    }




}
