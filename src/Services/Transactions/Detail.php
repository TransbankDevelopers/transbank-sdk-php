<?php

namespace Transbank\Sdk\Services\Transactions;

use ArrayAccess;
use JsonSerializable;

class Detail implements ArrayAccess, JsonSerializable
{
    use DynamicallyAccess;

    /**
     * ApiRequest constructor.
     *
     * @param  array  $data
     */
    public function __construct(
        protected array $data
    ) {
    }

    /**
     * Checks if the transaction was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['response_code']) && $this->data['response_code'] === 0;
    }
}
