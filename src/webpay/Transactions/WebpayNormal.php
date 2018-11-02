<?php

namespace Transbank\Webpay\Transactions;

use Exception;
use Transbank\Helpers\Fluent;
use Transbank\Webpay\SoapClassMaps\AcknowledgeTransaction;
use Transbank\Webpay\SoapClassMaps\GetTransactionResult;
use Transbank\Webpay\SoapClassMaps\WsInitTransactionInput;
use Transbank\Webpay\SoapClassMaps\WsTransactionDetail;
use Transbank\Webpay\SoapValidation;

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
    protected $resultCodesName = 'PlusMallNormal';

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
            $error = [];

            $wsInitTransactionInput = new Fluent([
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
            $initTransactionResponse = $this->performInitTransaction($wsInitTransactionInput);

            // Validates Webpay Response
            if ($this->validate()) {

                return $initTransactionResponse->return;

            } else {

                $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";
            }

        } catch (Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la información del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
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
            $getTransactionResultResponse = $this->performGetTransactionResult($getTransactionResult);

            // If Validation passes and the Transaction is Acknowledged...
            if ($this->validate() && $this->acknowledgeTransaction($token)) {

                // Extract the results from the response
                $transactionResultOutput = $getTransactionResultResponse->return;

                // And the result code too, forced as an integer
                $resultCode = intval($transactionResultOutput->detailOutput->responseCode);

                // Return the results if the transaction was a success, otherwise
                // get the reason why the transaction failed.
                if (($transactionResultOutput->VCI === 'TSY' || $transactionResultOutput->VCI === '')
                    && $resultCode === 0) {

                    return $transactionResultOutput;

                } else {
                    return $this->getReason($resultCode);
                }

            } else {
                $error["error"] = "Error validando conexi&oacute;n a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";
                $error["detail"] = "No se pudo completar la conexi&oacute;n con Webpay";
            }
        } catch (\Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)";

            $replaceArray = array('<!--' => '', '-->' => '');
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }

        return $error;
    }

}
