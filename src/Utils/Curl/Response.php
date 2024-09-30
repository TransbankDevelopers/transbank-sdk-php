<?php

namespace Transbank\Utils\Curl;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    private int $statusCode;
    private string $reasonPhrase;
    private string $protocolVersion = '1.1';
    private array $headers = [];
    private StreamInterface $body;

    private const PHRASES = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    public function __construct(int $statusCode = 200, array $headers = [], StreamInterface|string|null $body = null)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->reasonPhrase = self::PHRASES[$statusCode] ?? '';
        $this->body = $this->createBody($body);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase !== '' ? $reasonPhrase : (self::PHRASES[$code] ?? '');
        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): ResponseInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name): array
    {
        return isset($this->headers[$name]) ? [$this->headers[$name]] : [];
    }

    public function getHeaderLine($name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader($name, $value): ResponseInterface
    {
        $new = clone $this;
        $new->headers[$name] = (array) $value;
        return $new;
    }

    public function withAddedHeader($name, $value): ResponseInterface
    {
        $new = clone $this;
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withoutHeader($name): ResponseInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): ResponseInterface
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    private function createBody($body = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'rw+');
        if (!empty($body)) {
            fwrite($resource, $body);
        }

        return new Stream($resource);
    }
}
