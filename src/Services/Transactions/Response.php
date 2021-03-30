<?php

namespace Transbank\Sdk\Services\Transactions;

class Response
{
    /**
     * Redirection URL where the user should send a POST request.
     *
     * @var string
     */
    protected $url;

    /**
     * The token value that POST request should contain.
     *
     * @var string
     */
    protected $token;

    /**
     * Response constructor.
     *
     * @param  string  $token
     * @param  string  $url
     */
    public function __construct(string $token, string $url)
    {
        $this->token = $token;
        $this->url = $url;
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
