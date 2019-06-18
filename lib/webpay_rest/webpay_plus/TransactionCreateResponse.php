<?php


namespace Transbank\Webpay\WebpayPlus;


use Exception;

/**
 * Class TransactionCreateResponse
 *
 * @package Transbank\Webpay
 */
class TransactionCreateResponse
{
    /**
     * @var string|null $token
     */
    public $token;
    /**
     * @var string|null $token
     */
    public $url;

    /**
     * TransactionCreateResponse constructor.
     *
     * @param string|array An associative array (or string json_decode able to one
     * that includes a 'token' key and an 'url key
     *
     * @throws Exception When the value is not a string or an associative array,
     * or cannot be converted to one
     */
    public function __construct($json)
    {
        $this->fromJSON($json);
    }

    public function fromJSON($json)
    {
        if (is_string($json)) {
            $json = json_decode($json, true);
        }
        if (!is_array($json)) {
            throw new Exception('Given value must be an associative array or a string that can be converted to an associative array with json_decode()');
        }

        $this->setToken($json["token"]);
        $this->setUrl($json["url"]);
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
