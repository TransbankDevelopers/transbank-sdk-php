<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;

class TransactionCreateResponse
{
    /**
     * @var ?string
     */
    public $token;

    /**
     * @var ?string
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
     * @return ?string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}
