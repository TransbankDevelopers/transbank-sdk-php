<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class InscriptionStartResponse
{
    public ?string $token;
    public ?string $urlWebpay;

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
     * @return ?string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return ?string
     */
    public function getUrlWebpay(): ?string
    {
        return $this->urlWebpay;
    }
}
