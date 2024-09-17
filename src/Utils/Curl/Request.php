<?php

namespace Transbank\Utils\Curl;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

class Request implements RequestInterface
{
    private string $method;
    private UriInterface|string $uri;
    private array $headers = [];
    private string $protocolVersion = '';
    private StreamInterface $body;
    private string $requestTarget = '';

    public function __construct(string $method, UriInterface|string $uri, array $headers = [], StreamInterface|string|null $body = null, string $protocolVersion = '1.1')
    {
        $this->method = $method;
        $this->uri = is_string($uri) ? new Uri($uri) : $uri;
        $this->body = $this->createBody($body);
        $this->protocolVersion = $protocolVersion;
        $this->headers = $headers;
    }

    public function getRequestTarget(): string
    {
        if ($this->requestTarget === '') {
            $this->requestTarget = $this->uri->getPath() ?: '/';
            if ($this->uri->getQuery()) {
                $this->requestTarget .= '?' . $this->uri->getQuery();
            }
        }

        return $this->requestTarget;
    }

    public function withRequestTarget($requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): RequestInterface
    {
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || !$this->hasHeader('Host')) {
            $new->headers['Host'] = [$uri->getHost()];
        }

        return $new;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): RequestInterface
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
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader($name, $value): RequestInterface
    {
        $new = clone $this;
        $new->headers[$name] = (array) $value;
        return $new;
    }

    public function withAddedHeader($name, $value): RequestInterface
    {
        $new = clone $this;
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withoutHeader($name): RequestInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): RequestInterface
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
