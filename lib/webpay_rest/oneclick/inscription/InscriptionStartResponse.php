<?php


namespace Transbank\Webpay\Oneclick;


class InscriptionStartResponse
{

    public $token;
    public $urlWebpay;

    public function __construct($json)
    {
        $token = $json["token"];
        $urlWebpay = $json["url_webpay"];
        $this->setToken($token);
        $this->setUrlWebpay($urlWebpay);
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
     * @param mixed $urlWebpay
     *
     * @return InscriptionStartResponse
     */
    public function setUrlWebpay($urlWebpay)
    {
        $this->urlWebpay = $urlWebpay;
        return $this;
    }
}
