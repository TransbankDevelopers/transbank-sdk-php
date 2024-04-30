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
     * @return mixed
     */
    public function getUrlWebpay()
    {
        return $this->urlWebpay;
    }

}
