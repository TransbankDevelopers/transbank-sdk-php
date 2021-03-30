<?php

namespace Transbank\Sdk\Events;

use Transbank\Sdk\ApiRequest;

class TransactionCompleted
{
    /**
     * Completed transaction.
     *
     * @var \Transbank\Sdk\ApiRequest
     * @example Creation, refunds, captures.
     */
    public $apiRequest;

    /**
     * Raw response from Transbank.
     *
     * @var array
     */
    public $response;

    /**
     * TransactionCompleted constructor.
     *
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest  Data sent to Transbank.
     * @param  array  $response  Raw response from Transbank.
     */
    public function __construct(ApiRequest $apiRequest, array $response)
    {
        $this->response = $response;
        $this->apiRequest = $apiRequest;
    }
}
