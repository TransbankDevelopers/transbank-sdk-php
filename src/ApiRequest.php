<?php

namespace Transbank\Sdk;

use ArrayAccess;
use JsonSerializable;

class ApiRequest implements JsonSerializable, ArrayAccess
{
    /**
     * Service Action name
     *
     * @var string
     * @example "webpay.create"
     */
    public string $serviceAction;

    /**
     * Key-value array to send to Transbank as JSON.
     *
     * @var array
     */
    public array $attributes = [];

    /**
     * ApiRequest constructor.
     *
     * @param  string  $serviceAction
     * @param  array  $attributes
     */
    public function __construct(string $serviceAction, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->serviceAction = $serviceAction;
    }

    /**
     * Returns a JSON representation of the transaction.
     *
     * @return string
     */
    public function toJson(): string
    {
        if (empty($this->attributes)) {
            return '';
        }

        return json_encode($this->jsonSerialize(), JSON_ERROR_NONE);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return mixed
     */
    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    /**
     * Whether a offset exists.
     *
     * @param  mixed  $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Offset to retrieve.
     *
     * @param  mixed  $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    /**
     * Offset to set.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Offset to unset.
     *
     * @param  mixed  $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }
}
