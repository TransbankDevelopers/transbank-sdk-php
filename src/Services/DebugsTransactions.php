<?php

namespace Transbank\Sdk\Services;

trait DebugsTransactions
{
    /**
     * Debugs a given operation.
     *
     * @param  string  $message
     * @param  array  $context
     */
    protected function log(string $message, array $context = []): void
    {
        $this->transbank->logger->debug($message, $context);
    }

    /**
     * Debugs a given operation.
     *
     * @param  array  $context
     */
    protected function logResponse(array $context = []): void
    {
        $this->transbank->logger->debug('Response received', $context);
    }
}
