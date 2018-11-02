<?php


namespace Transbank\Webpay\Transactions\Concerns;

/**
 * Trait InitializesTransactions
 * @package Transbank\Webpay\Transactions\Concerns
 *
 * @mixin \Transbank\Webpay\Transactions\Transaction
 */
trait InitializesTransactions
{

    /**
     * Performs the Initialization of the Transaction on Webpay
     *
     * @param object $transaction
     * @return mixed
     */
    protected function performInitTransaction($transaction)
    {
        return $this->soapClient->initTransaction([
            'wsInitTransactionInput' => $transaction
        ]);
    }
}
