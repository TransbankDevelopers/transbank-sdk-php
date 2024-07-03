<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class InscriptionStartResponse
{
    public string|null $token;
    public string|null $urlWebpay;

    public function __construct(array $json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
        $this->urlWebpay = Utils::returnValueIfExists($json, 'url_webpay');
    }

    public function getRedirectUrl(): string
    {
        return $this->getUrlWebpay() . '?TBK_TOKEN=' . $this->getToken();
    }

    /**
     * @return string|null
     */
    public function getToken(): string|null
    {
        return $this->token;
    }

    /**
     * @return string|null
     */
    public function getUrlWebpay(): string|null
    {
        return $this->urlWebpay;
    }
}
