<?php

namespace Transbank\Sdk\Services\Transactions;

use ArrayAccess;
use JsonSerializable;

class TransactionDetail implements ArrayAccess, JsonSerializable
{
    use DynamicallyAccess;

    /**
     * Transaction detail data.
     *
     * @var array
     */
    protected $data;

    /**
     * ApiRequest constructor.
     *
     * @param  array  $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
