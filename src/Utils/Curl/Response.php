<?php

namespace Transbank\Utils\Curl;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    use MessageTrait;
    private int $statusCode;
    private string $reasonPhrase;
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
}
