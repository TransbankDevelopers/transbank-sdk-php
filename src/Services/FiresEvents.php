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
     * @param  \Transbank\Sdk\ApiRequest  $transaction
     */
    protected function fireStarted(ApiRequest $transaction): void
    {
        $this->transbank->event->dispatch(new TransactionCreating($transaction));
    }

    /**
     * Fires a ApiRequest Completed event.
     *
     * @param  \Transbank\Sdk\ApiRequest  $transaction
     * @param  array  $response
     */
    protected function fireCompleted(ApiRequest $transaction, array $response): void
    {
        $this->transbank->event->dispatch(new TransactionCompleted($transaction, $response));
    }
}
