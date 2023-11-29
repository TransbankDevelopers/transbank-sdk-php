<?php

/**
 * Class InscriptionStartResponse.
 *
 * @category
 */

namespace Transbank\Patpass\PatpassComercio\Responses;

use Transbank\Utils\Utils;

class InscriptionStartResponse
{
    public $token;
    public $urlWebpay;

    /**
     * InscriptionStartResponse constructor.
     *
     * @param $token
     */
    public function __construct($json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
        $this->urlWebpay = Utils::returnValueIfExists($json, 'url');
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
