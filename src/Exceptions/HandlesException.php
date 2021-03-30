<?php

namespace Transbank\Sdk\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Transbank\Sdk\ApiRequest;

trait HandlesException
{
    protected ?ApiRequest $apiRequest = null;
    protected ?ServerRequestInterface $request = null;
    protected ?ResponseInterface $response = null;

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
        ?ApiRequest $apiRequest = null,
        ?ServerRequestInterface $request = null,
        ?ResponseInterface $response = null,
        ?Throwable $previous = null
    ) {
        $this->response = $response;
        $this->request = $request;
        $this->apiRequest = $apiRequest;

        parent::__construct($message, static::LOG_LEVEL, $previous);
    }

    /**
     * Returns the ApiRequest of this exception, if any.
     *
     * @return \Transbank\Sdk\ApiRequest|null
     */
    public function getApiRequest(): ?ApiRequest
    {
        return $this->apiRequest;
    }

    /**
     * Returns the Server Request sent to Transbank, if any.
     *
     * @return \Psr\Http\Message\ServerRequestInterface|null
     */
    public function getServerRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Returns the Response from Transbank, if any.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
