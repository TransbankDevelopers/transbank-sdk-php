<?php

namespace Transbank\Webpay\Transactions\Concerns;

/**
 * Class PerformsGetTransactionResults
 *
 * @package Transbank\Webpay\Transactions\Concerns
 *
 * @mixin \Transbank\Webpay\Transactions\Transaction
 */
trait PerformsGetTransactionResults
{

    /**
     * Returns the Transaction results
     *
     * @param $transaction
     * @return mixed
     */
    protected function performGetTransactionResult($transaction)
    {
        return $this->soapClient->getTransactionResult($transaction);
    }
}
