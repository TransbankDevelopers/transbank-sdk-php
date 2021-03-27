<?php

namespace Tests\Services;

use Psr\Http\Message\ServerRequestInterface;
use Transbank\Sdk\Http\Connector;

trait AssertsApiEndpoint
{
    protected static function assertApiEndpoint(string $endpoint, ServerRequestInterface $request, array $replace = []): void
    {
        $endpoint = str_replace(
            array_merge(['{api_version}'], array_keys($replace)),
            array_merge([Connector::API_VERSION], $replace),
            $endpoint
        );

        static::assertEquals('/' . $endpoint, $request->getUri()->getPath());
    }
}
