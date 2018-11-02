<?php


namespace Transbank\Webpay\Transactions\Concerns;

use Transbank\Helpers\Fluent;

/**
 * Trait AcknowledgesTransactions
 * @package Transbank\Webpay\Transactions\Concerns
 *
 * @mixin \Transbank\Webpay\Transactions\Transaction
 */
trait AcknowledgesTransactions
{
    /**
     * Notifies Webpay that the Transaction has been accepted
     *
     * @param $transaction
     * @return mixed
     */
    protected function performAcknowledge($transaction)
    {
        return $this->soapClient->acknowledgeTransaction($transaction);
    }

    /**
     * Acknowledges and accepts the Transaction
     *
     * @param $token
     * @return bool
     */
    public function acknowledgeTransaction($token)
    {
        $acknowledgeTransaction = new Fluent([
            'tokenInput' => $token
        ]);

        $this->performAcknowledge($acknowledgeTransaction);

        // Since we don't need any result, return the validation
        return $this->validate();
    }
}
