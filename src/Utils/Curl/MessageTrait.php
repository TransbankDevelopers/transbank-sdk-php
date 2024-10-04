<?php

namespace Transbank\Utils\Curl;

use Psr\Http\Message\StreamInterface;
use Transbank\Utils\Curl\Exceptions\CurlRequestException;

trait MessageTrait
{
    private string $protocolVersion = '';
    private array $headers = [];
    private StreamInterface $body;

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): static
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

    public function withoutHeader($name): static
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): static
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function withHeader($name, $value): static
    {
        $new = clone $this;
        $new->headers[$name] = (array) $value;
        return $new;
    }

    public function withAddedHeader($name, $value): static
    {
        $new = clone $this;
        $new->headers[$name][] = $value;
        return $new;
    }

    private function createBody($body = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'rw+');
        if ($resource === false) {
            throw new CurlRequestException('Unable to open stream');
        }

        if (!empty($body)) {
            $writtenBytes = fwrite($resource, $body);
            if ($writtenBytes === false) {
                throw new CurlRequestException('Unable to write to stream');
            }
        }

        return new Stream($resource);
    }
}
