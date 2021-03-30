<?php

namespace Transbank\Sdk\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Transbank\Sdk\ApiRequest;

interface TransbankException extends Throwable
{
    /**
     * Returns the ApiRequest of this exception, if any.
     *
     * @return \Transbank\Sdk\ApiRequest|null
     */
    public function getApiRequest(): ?ApiRequest;

    /**
     * Returns the Server Request sent to Transbank, if any.
     *
     * @return \Psr\Http\Message\ServerRequestInterface|null
     */
    public function getServerRequest(): ?ServerRequestInterface;

    /**
     * Returns the Response from Transbank, if any.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface;
}
