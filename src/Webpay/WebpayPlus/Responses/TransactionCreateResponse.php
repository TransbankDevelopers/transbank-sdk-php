<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;

class TransactionCreateResponse
{
    /**
     * @var string|null
     */
    public string|null $token;

    /**
     * @var string|null
     */
    public string|null $url;

    /**
     * TransactionCreateResponse constructor.
     *
     * @param array $json an associative array with keys 'token', 'url'
     *                    + token (string, required) - the token returned from a successful call
     *                    to 'create'
     *                    + url (string, required) - the url returned from a successful call to
     *                    'create'
     */
    public function __construct(array $json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
        $this->url = Utils::returnValueIfExists($json, 'url');
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
    public function fromJSON(array $json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
        $this->url = Utils::returnValueIfExists($json, 'url');

        return $this;
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
    public function getUrl(): string|null
    {
        return $this->url;
    }
}
