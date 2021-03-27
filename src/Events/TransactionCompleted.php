<?php

namespace Transbank\Sdk\Events;

use Transbank\Sdk\ApiRequest;

class TransactionCompleted
{
    /**
     * TransactionCompleted constructor.
     *
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest  Data sent to Transbank.
     * @param  array  $response  Raw response from Transbank.
     */
    public function __construct(
        public ApiRequest $apiRequest,
        public array $response
    ) {
    }
}
