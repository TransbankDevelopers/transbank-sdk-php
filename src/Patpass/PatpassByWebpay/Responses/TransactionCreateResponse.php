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
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

}
