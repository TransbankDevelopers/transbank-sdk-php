<?php
namespace Transbank\Webpay\Transactions;

use Exception;
use Transbank\Helpers\Fluent;

class WebpayCompleteTransaction extends Transaction
{
    /**
     * URL for the environment
     *
     * @var array
     */
    protected static $WSDL_URL_NORMAL = [
        'integration'   => 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCompleteWebpayService?wsdl',
        'production'    => 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCompleteWebpayService?wsdl',
    ];

    /**
     * Filename to include into the Result Codes array
     *
     * @var string
     */
    protected $resultCodesName = '';

    /**
     * Class Map to require
     *
     * @var string
     */
    protected $classMapName = 'complete';

    /**
     * Initializes a transaction Webpay
     *
     * @param $amount
     * @param $buyOrder
     * @param $sessionId
     * @param $cardExpirationDate
     * @param $cvv
     * @param $cardNumber
     * @return mixed
     */
    public function initCompleteTransaction($amount, $buyOrder, $sessionId, $cardExpirationDate, $cvv, $cardNumber)
    {

        try {

            $transaction = new Fluent([
                // Type of transactions. For this, it has to always be 'TR_COMLETA_WS'.
                'transactionType' => 'TR_COMPLETA_WS',
                'sessionId' => $sessionId,
                // Object with Credit Card information
                'cardDetail' => new Fluent([
                    'cardExpirationDate' => $cardExpirationDate,
                    'cvv' => $cvv,
                    'cardNumber' => $cardNumber,
                ]),
                // Object with all unique transaction details
                'transactionDetails' => new Fluent([
                    'amount' => $amount,
                    'buyOrder' => $buyOrder,
                    'commerceCode' => $this->config->getCommerceCode(),
                ])
            ]);


            // Perform the transaction
            $response = $this->performInitCompleteTransaction($transaction);

            // If the validation is successful, return the results
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }

    }

    /**
     * Returns the installments values
     *
     * @param $token
     * @param $buyOrder
     * @param $shareNumber
     * @return mixed
     */
    public function queryShare($token, $buyOrder, $shareNumber)
    {
        try {

            $queryShare = new Fluent([
                'token' => $token,
                'buyOrder' => $buyOrder,
                'shareNumber' => $shareNumber,
            ]);

            // Perform the Query Share transaction
            $response = $this->performQueryShare($queryShare);


            // If the validation is successful, return the results
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

    /**
     * Authorizes the transaction, with or without installments.
     *
     * @param $token
     * @param $buyOrder
     * @param $gracePeriod
     * @param $idQueryShare
     * @param int $deferredPeriodIndex
     * @return mixed
     */
    public function authorize($token, $buyOrder, $gracePeriod, $idQueryShare, $deferredPeriodIndex)
    {
        try {

            $authorize = new Fluent([
                'token' => $token,
                'paymentTypeList' => new Fluent([
                    'buyOrder' => $buyOrder,
                    'commerceCode' => $this->config->getCommerceCode(),
                    'gracePeriod' => $gracePeriod,
                    'queryShareInput' => new Fluent([
                        'idQueryShare' => $idQueryShare,
                    ])
                ])
            ]);

            // Get a installment by the given offset.
            if ($deferredPeriodIndex !== 0) {
                $authorize->paymentTypeList->queryShareInput->deferredPeriodIndex = $deferredPeriodIndex;
            }

            // Perform the authorization
            $response = $this->performAuthorize($authorize);

            // If the validation is successful, return the results
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

    /**
     * Acknowledges the Transaction result
     *
     * @param $token
     * @return bool
     */
    public function acknowledgeCompleteTransaction($token)
    {
        $acknowledgeTransaction = new Fluent([
            'tokenInput' => $token
        ]);

        // Perform the Transaction
        $this->performAcknowledge($acknowledgeTransaction);

        // Since we don't need the results, we just simply return if the validation passes
        return $this->validate();

    }

    /**
     * Performs the acknowledge to Webpay, which means to accept the transaction result
     *
     * @param $transaction
     * @return mixed
     */
    protected function performAcknowledge($transaction)
    {
        return $this->soapClient->acknowledgeCompleteTransaction($transaction);
    }

    /**
     * Authorizes the transaction, with or without installments ("cuotas").
     *
     * @param $transaction
     * @return mixed
     */
    protected function performAuthorize($transaction)
    {
        return $this->soapClient->authorize($transaction);
    }

    /**
     * Allows to retrieve each installment value
     *
     * @param $queryShare
     * @return mixed
     */
    protected function performQueryShare($queryShare)
    {
        return $this->soapClient->queryShare($queryShare);
    }

    /**
     * Initializes a transaction in Webpay, returning the token transaction
     *
     * @param $transaction
     * @return mixed
     */
    protected function performInitCompleteTransaction($transaction)
    {
        return $this->soapClient->initCompleteTransaction([
            'wsCompleteInitTransactionInput' => $transaction
        ]);
    }

}
