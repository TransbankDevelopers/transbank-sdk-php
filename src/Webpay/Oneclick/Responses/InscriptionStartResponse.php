<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class InscriptionStartResponse
{
    public $token;
    public $urlWebpay;

    public function __construct($json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
        $this->urlWebpay = Utils::returnValueIfExists($json, 'url_webpay');
    }

    public function getRedirectUrl()
    {
        return $this->getUrlWebpay().'?TBK_TOKEN='.$this->getToken();
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
