<?php


namespace Transbank\PatpassByWebpay;


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
        $token = isset($json['token']) ? $json['token'] : null;
        $this->setToken($token);
        $url = isset($json['url']) ? $json['url'] : null;
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


    /*{
    "token": "e9d555262db0f989e49d724b4db0b0af367cc415cde41f500a776550fc5fddd3",
    "url": "https://webpay3gint.transbank.cl/webpayserver/initTransaction"
    }*/
}
