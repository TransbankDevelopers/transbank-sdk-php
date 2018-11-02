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

            $wsCompleteInitTransactionInput = new Fluent([
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
            $initCompleteTransactionResponse = $this->performInitCompleteTransaction(
                $wsCompleteInitTransactionInput
            );

            // Return the results if the validation is true
            if ($this->validate()) {
                return $initCompleteTransactionResponse->return;
            } else {
                $error["error"] = "Error validando conexión a Webpay (Verifica que la informaciónn del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexión con Webpay";
            }
        } catch (Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la información del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
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
            $queryShareResponse = $this->performQueryShare($queryShare);

            // Return the results if the validation passes
            if ($this->validate()) {

                return $queryShareResponse->return;

            } else {

                $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";

            }
        } catch (Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
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

            // I think this is for getting a installment by the given offset.
            if ($deferredPeriodIndex !== 0) {
                $authorize->paymentTypeList->queryShareInput->deferredPeriodIndex = $deferredPeriodIndex;
            }

            // Perform the authorization
            $authorizeResponse = $this->performAuthorize($authorize);


            // Return the results if validation passes
            if ($this->validate()) {

                // Before returning the results, acknowledge the transaction
                if ($this->acknowledgeCompleteTransaction($token)) {
                    return $authorizeResponse->return;
                }

                $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";

            }
        } catch (Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        /** @var array $error */
        return $error;
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
     * @param $acknowledge
     * @return mixed
     */
    protected function performAcknowledge($acknowledge)
    {
        return $this->soapClient->acknowledgeCompleteTransaction($acknowledge);
    }

    /**
     * Authorizes the transaction, with or without installments ("cuotas").
     *
     * @param $authorize
     * @return mixed
     */
    protected function performAuthorize($authorize)
    {
        return $this->soapClient->authorize($authorize);
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
     * @param $initCompleteTransaction
     * @return mixed
     */
    protected function performInitCompleteTransaction($initCompleteTransaction)
    {
        return $this->soapClient->initCompleteTransaction([
            'wsCompleteInitTransactionInput' => $initCompleteTransaction
        ]);
    }

}
