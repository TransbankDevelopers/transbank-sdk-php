<?php

namespace Transbank\Utils\Curl;

use Psr\Http\Message\StreamInterface;
use Transbank\Utils\Curl\Exceptions\StreamException;

class Stream implements StreamInterface
{
    private $resource;

    public function __construct($resource = '')
    {
        if (!is_resource($resource)) {
            throw new \InvalidArgumentException('Stream must be a resource');
        }
        $this->resource = $resource;
    }

    public function __toString(): string
    {
        try {
            if ($this->isSeekable()) {
                $this->rewind();
            }
            return stream_get_contents($this->resource);
        } catch (\Exception $e) {
            return '';
        }
    }

    public function close(): void
    {
        if ($this->resource) {
            fclose($this->resource);
        }
    }

    public function detach()
    {
        $resource = $this->resource;
        $this->resource = null;
        return $resource;
    }

    public function getSize(): ?int
    {
        if ($this->resource) {
            $stats = fstat($this->resource);
            return $stats['size'] ?? null;
        }
        return null;
    }

    public function tell(): int
    {
        if ($this->resource === null) {
            throw new StreamException('Stream is not open.');
        }

        $position = ftell($this->resource);
        if ($position === false) {
            throw new StreamException('Unable to determine stream position.');
        }

        return $position;
    }

    public function eof(): bool
    {
        return $this->resource ? feof($this->resource) : true;
    }

    public function isSeekable(): bool
    {
        return $this->resource ? (bool)stream_get_meta_data($this->resource)['seekable'] : false;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new StreamException('Stream is not seekable.');
        }

        if (fseek($this->resource, $offset, $whence) === -1) {
            throw new StreamException('Unable to seek in stream.');
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        if (!$this->resource) {
            return false;
        }

        $mode = stream_get_meta_data($this->resource)['mode'];
        return strpbrk($mode, 'w+') !== false;
    }

    public function write($string): int
    {
        if (!$this->isWritable()) {
            throw new StreamException('Stream is not writable.');
        }

        $result = fwrite($this->resource, $string);
        if ($result === false) {
            throw new StreamException('Unable to write to stream.');
        }

        return $result;
    }

    public function isReadable(): bool
    {
        if (!$this->resource) {
            return false;
        }

        $mode = stream_get_meta_data($this->resource)['mode'];
        return strpbrk($mode, 'r+') !== false;
    }

    public function read($length): string
    {
        if (!$this->isReadable()) {
            throw new StreamException('Stream is not readable.');
        }

        $result = fread($this->resource, $length);
        if ($result === false) {
            throw new StreamException('Unable to read from stream.');
        }

        return $result;
    }

    public function getContents(): string
    {
        if (!$this->resource) {
            throw new StreamException('Stream is not open.');
        }

        $contents = stream_get_contents($this->resource);
        if ($contents === false) {
            throw new StreamException('Unable to read stream contents.');
        }

        return $contents;
    }

    public function getMetadata($key = null)
    {
        if (!$this->resource) {
            return null;
        }

        $meta = stream_get_meta_data($this->resource);
        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }
}
