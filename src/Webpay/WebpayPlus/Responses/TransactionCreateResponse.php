<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

class TransactionCreateResponse
{
    /**
     * @var string|null
     */
    public $token;

    /**
     * @var string|null
     */
    public $url;

    /**
     * TransactionCreateResponse constructor.
     *
     * @param array $json an associative array with keys 'token', 'url'
     *                    + token (string, required) - the token returned from a successful call
     *                    to 'create'
     *                    + url (string, required) - the url returned from a successful call to
     *                    'create'
     */
    public function __construct($json)
    {
        $this->fromJSON($json);
    }

    /**
     * @param array $json an associative array with keys 'token', 'url'
     *                    + token (string, required) - the token returned from a successful call
     *                    to 'create'
     *                    + url (string, required) - the url returned from a successful call to
     *                    'create'
     *
     * @return $this
     */
    public function fromJSON($json)
    {
        $this->setToken($json['token']);
        $this->setUrl($json['url']);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     *
     * @return TransactionCreateResponse
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return TransactionCreateResponse
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
