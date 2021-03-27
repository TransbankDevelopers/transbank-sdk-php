<?php

namespace Transbank\Sdk\Services\Transactions;

class Response
{
    /**
     * Response constructor.
     *
     * @param  string  $token
     * @param  string  $url
     */
    public function __construct(
        protected string $token,
        protected string $url
    ) {
    }

    /**
     * Returns the transaction token that identifies it on Transbank.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Returns the transaction URL where the transaction can be retrieved.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
