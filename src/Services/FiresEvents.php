<?php

namespace Transbank\Sdk\Services;

use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Events\TransactionCompleted;
use Transbank\Sdk\Events\TransactionCreating;

trait FiresEvents
{
    /**
     * Fires a ApiRequest Started event.
     *
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     */
    protected function fireStarted(ApiRequest $apiRequest): void
    {
        $this->transbank->event->dispatch(new TransactionCreating($apiRequest));
    }

    /**
     * Fires a ApiRequest Completed event.
     *
     * @param  \Transbank\Sdk\ApiRequest  $apiRequest
     * @param  array  $response
     */
    protected function fireCompleted(ApiRequest $apiRequest, array $response): void
    {
        $this->transbank->event->dispatch(new TransactionCompleted($apiRequest, $response));
    }
}
