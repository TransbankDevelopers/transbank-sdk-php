<?php

namespace Transbank\Patpass\PatpassByWebpay\Responses;

use Transbank\Utils\Utils;

class TransactionCreateResponse
{
    public $token;
    public $url;

    /**
     * TransactionCreateResponse constructor.
     *
     * @param $json
     */
    public function __construct($json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
        $this->url = Utils::returnValueIfExists($json, 'url');
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
     * @return TransactionCreateResponse
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
     * @return TransactionCreateResponse
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
