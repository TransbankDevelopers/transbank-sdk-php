<?php

namespace Transbank\Sdk\Events;

use Transbank\Sdk\ApiRequest;

class TransactionCreating
{
    /**
     * TransactionStarted constructor.
     *
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     */
    public function __construct(
        public ApiRequest $apiRequest
    ) {
    }
}
