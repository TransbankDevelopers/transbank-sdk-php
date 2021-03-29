<?php /** @noinspection JsonEncodingApiUsageInspection */

namespace Tests\Services;

use Psr\Http\Message\ServerRequestInterface;
use Transbank\Sdk\Http\Connector;

trait AssertsApiEndpoint
{
    protected static function assertEndpointPath(string $endpoint, ServerRequestInterface $request, array $replace = []): void
    {
        $endpoint = str_replace(
            array_merge(['{api_version}'], array_keys($replace)),
            array_merge([Connector::API_VERSION], $replace),
            $endpoint
        );

        static::assertEquals('/' . $endpoint, $request->getUri()->getPath());
    }

    protected static function assertRequestContentsEmpty(ServerRequestInterface $request): void
    {
        $stream = $request->getBody();
        $stream->rewind();

        static::assertEmpty($stream->getContents());
    }

    protected static function assertRequestContents(array $contents, ServerRequestInterface $request): void
    {
        $stream = $request->getBody();
        $stream->rewind();

        static::assertEquals(json_encode($contents), $stream->getContents());
    }
}
