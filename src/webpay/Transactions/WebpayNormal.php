<?php

namespace Transbank\Webpay\Transactions;

use Exception;
use Transbank\Helpers\Fluent;

/**
 * TRANSACCIÓN DE AUTORIZACIÓN NORMAL:
 *
 * Una transacción de autorización normal (o transacción normal), corresponde a una solicitud de
 * autorización financiera de un pago con tarjetas de crédito o débito, en donde quién realiza el pago
 * ingresa al sitio del comercio, selecciona productos o servicio, y el ingreso asociado a los datos de la
 * tarjeta de crédito o débito lo realiza en forma segura en Webpay.
 */

/**
 * Class WebpayNormal
 *
 * @package Transbank\Webpay\Transactions
 */
class WebpayNormal extends Transaction
{
    use Concerns\AcknowledgesTransactions,
        Concerns\PerformsGetTransactionResults,
        Concerns\InitializesTransactions;

    /**
     * @var array
     */
    protected static $WSDL_URL_NORMAL = [
        'integration' => 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl',
        'production' => 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl',
    ];

    /**
     * Class Map to require
     *
     * @var string
     */
    protected $classMapName = 'normal';

    /**
     * Filename to include into the Result Codes array
     *
     * @var string
     */
    protected $resultCodesName = 'plusnormal';

    /**
     * Initializes the transaction
     *
     * @param string $amount
     * @param string $buyOrder
     * @param string $sessionId
     * @param string $urlReturn
     * @param string $urlFinal
     * @return array
     */
    public function initTransaction($amount, $buyOrder, $sessionId, $urlReturn, $urlFinal)
    {

        try {
            $transaction = new Fluent([
                'wSTransactionType' => "TR_NORMAL_WS",
                'sessionId' => $sessionId,
                'buyOrder' => $buyOrder,
                'returnURL' => $urlReturn,
                'finalURL' => $urlFinal,
                'transactionDetails' => new Fluent([
                    'commerceCode' => $this->config->getCommerceCode(),
                    'buyOrder' => $buyOrder,
                    'amount' => $amount,
                ])
            ]);

            // Perform the Initialization of the Transaction
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
     * Obtains the Transaction results from Webpay
     *
     * @param string $token
     * @return mixed
     */
    public function getTransactionResult($token)
    {

        try {

            $getTransactionResult = new Fluent([
                'tokenInput' => $token
            ]);

            // Perform the Transaction result
            $response = $this->performGetTransactionResult($getTransactionResult);

            // If Validation passes and the Transaction is Acknowledged...
            if ($this->validate() && $this->acknowledgeTransaction($token)) {

                // Extract the results from the response
                $response = $response->return;

                // And the result code too, forced as an integer
                $resultCode = intval($response->detailOutput->responseCode);

                // Return the results if the transaction was a success, otherwise
                // return the reason why the transaction failed through the
                // results codes
                return ($response->VCI === 'TSY' || $response->VCI === '') && $resultCode === 0
                    ? $response
                    : $this->getReason($resultCode);

            } else {
                return $this->returnValidationErrorArray();
            }
        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }

    }

}
