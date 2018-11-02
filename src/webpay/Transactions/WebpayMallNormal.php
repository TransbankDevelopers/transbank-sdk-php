<?php

namespace Transbank\Webpay\Transactions;

use Exception;
use Transbank\Helpers\Fluent;

/**
 * Class WebpayMallNormal
 *
 * @package Transbank\Webpay\Transactions
 */
class WebpayMallNormal extends Transaction
{
    use Concerns\AcknowledgesTransactions,
        Concerns\PerformsGetTransactionResults,
        Concerns\InitializesTransactions;

    /**
     * URL for the environment
     *
     * @var array
     */
    protected static $WSDL_URL_NORMAL = [
        'integration' => 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl',
        'production' => 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl',
    ];

    /**
     * Filename to include into the Result Codes array
     *
     * @var string
     */
    protected $resultCodesName = 'plusnormal';

    /**
     * Initializes and creates an Transaction in Webpay
     *
     * @param $buyOrder
     * @param $sessionId
     * @param $urlReturn
     * @param $urlFinal
     * @param array $stores
     * @return array
     */
    public function initTransaction($buyOrder, $sessionId, $urlReturn, $urlFinal, array $stores)
    {
        try {

            $transaction = new Fluent([
                'wSTransactionType' => 'TR_MALL_WS',
                'commerceId' => $this->config->getCommerceCode(),
                'sessionId' => $sessionId,
                'buyOrder' => $buyOrder,
                'returnURL' => $urlReturn,
                'finalURL' => $urlFinal,
            ]);

            $transactionDetails = [];

            // Add every store to the Transaction input
            foreach ($stores as $value) {
                $transactionDetails[] = new Fluent([
                    'commerceCode' => $value['storeCode'],
                    'buyOrder' => floatval($value['buyOrder']),
                    'amount' => $value['amount'],
                ]);
            }

            // Add the array to the Transaction Input
            $transaction->transactionDetails = $transactionDetails;

            // Perform the Transaction
            $response = $this->performInitTransaction($transaction);

            // If the validation is successful, return the results
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

    /**
     * Get the Transaction result from Webpay once it resolves the financial authorization
     *
     * @param string $token
     * @return mixed
     */
    public function getTransactionResult($token)
    {

        try {

            $transaction = new Fluent([
                'tokenInput' => $token,
            ]);

            // Perform the transaction
            $response = $this->performGetTransactionResult($transaction);

            if ($this->validate()) {

                // Acknowledge the Transaction before returning the results
                if ($this->acknowledgeTransaction($token)) {
                    return $response->return;
                }

                return $this->returnValidationErrorArray();
            }
        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

}
