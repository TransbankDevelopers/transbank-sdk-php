<?php

namespace Transbank\Sdk\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Transbank\Sdk\ApiRequest;

trait HandlesException
{
    /**
     * Transbank Exception constructor.
     *
     * @param  string  $message
     * @param  \Transbank\Sdk\ApiRequest|null  $apiRequest
     * @param  \Psr\Http\Message\ServerRequestInterface|null  $request
     * @param  \Psr\Http\Message\ResponseInterface|null  $response
     * @param  Throwable|null  $previous
     */
    public function __construct(
        string $message = '',
        protected ApiRequest|null $apiRequest = null,
        protected ServerRequestInterface|null $request = null,
        protected ResponseInterface|null $response = null,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, static::LOG_LEVEL, $previous);
    }

    /**
     * Returns the ApiRequest of this exception, if any.
     *
     * @return \Transbank\Sdk\ApiRequest|null
     */
    public function getApiRequest(): ApiRequest|null
    {
        return $this->apiRequest;
    }

    /**
     * Returns the Server Request sent to Transbank, if any.
     *
     * @return \Psr\Http\Message\ServerRequestInterface|null
     */
    public function getServerRequest(): ServerRequestInterface|null
    {
        return $this->request;
    }

    /**
     * Returns the Response from Transbank, if any.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse(): ResponseInterface|null
    {
        return $this->response;
    }
}
