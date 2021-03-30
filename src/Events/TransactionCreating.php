<?php

namespace Transbank\Sdk\Events;

use Transbank\Sdk\ApiRequest;

class TransactionCreating
{
    /**
     * Completed transaction.
     *
     * @var \Transbank\Sdk\ApiRequest
     * @example Creation, refunds, captures.
     */
    public $apiRequest;

    /**
     * TransactionStarted constructor.
     *
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     */
    public function __construct(ApiRequest $apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }
}
