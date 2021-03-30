<?php

namespace Transbank\Sdk\Services\Transactions;

use BadMethodCallException;

trait DynamicallyAccess
{
    /**
     * Handle a dynamic call
     *
     * @param  string  $method  Like "property_one" or "propertyOne".
     * @param  array  $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments): mixed
    {
        // If the call starts with "get", the developer is getting a property.
        if (strlen($method) > 3 && str_starts_with($method, 'get') && ctype_upper($method[3])) {
            $name = substr($method, 3);

            // The name of the method could be "camelCase" so let's try that
            // first. If no key isn't found, we will try to "snake_case" it
            // and look for it. Ultimately, we should return an exception.
            if (isset($this->data[$name]) || isset($this->data[$name = self::toSnakeCase($name)])) {
                return $this->data[$name];
            }
        }

        // Since there is no key matching the name, bail.
        throw new BadMethodCallException("Method $method does not exist");
    }

    /**
     * This function happily returns the key of a response using snake case.
     *
     * @param  string  $method
     *
     * @return string
     */
    protected static function toSnakeCase(string $method): string
    {
        return strtolower(ltrim(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $method), '_'));
    }

    /**
     * Dynamically return a key from their properties.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        // Use a different IF block to keep the original property call.
        if (ctype_lower($name[0]) && isset($this->data[$snake = self::toSnakeCase($name)])) {
            return $this->data[$snake];
        }

        // The property doesn't exists, so bail out.
        trigger_error("Undefined property: " . __CLASS__ . '::$' . $name, E_USER_ERROR);
    }

    /**
     * Disable setting a property.
     *
     * @param  string  $name
     * @param  mixed  $value
     */
    public function __set(string $name, mixed $value): void
    {
        // Immutable
    }

    /**
     * Checks if a property exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->data[$name]) || isset($this->data[self::toSnakeCase($name)]);
    }

    /**
     * Whether a offset exists
     *
     * @param  mixed  $offset
     *
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param  mixed  $offset
     *
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * Offset to set
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Immutable.
    }

    /**
     * Offset to unset
     *
     * @param  mixed  $offset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        // Immutable
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * Transforms this transaction to a JSON string.
     *
     * @return string
     * @throws \JsonException
     */
    public function toJson(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }
}
