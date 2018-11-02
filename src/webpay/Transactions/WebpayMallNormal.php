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
    protected $resultCodesName = 'PlusMallNormal';

    /**
     * Class Map to require
     *
     * @var string
     */
    protected $classMapName = 'mall';


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
            $error = [];

            $wsInitTransactionInput = new Fluent([
                'wSTransactionType' => 'TR_MALL_WS',
                'commerceId' => $this->config->getCommerceCode(),
                'sessionId' => $sessionId,
                'buyOrder' => $buyOrder,
                'returnURL' => $urlReturn,
                'finalURL' => $urlFinal,
            ]);

            $transactionDetails = [];

            // Add every store to the Transaction input
            foreach (array_values($stores) as $value) {
                $transactionDetails[] = new Fluent([
                    'commerceCode' => $value['storeCode'],
                    'buyOrder' => floatval($value['buyOrder']),
                    'amount' => $value['amount'],
                ]);
            }

            // Add the array to the Transaction Input
            $wsInitTransactionInput->transactionDetails = $transactionDetails;

            // Perform the Transaction
            $initTransactionResponse = $this->performInitTransaction($wsInitTransactionInput);

            // Return the Response if the validation passes
            if ($this->validate()) {
                return $initTransactionResponse->return;

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
     * Get the Transaction result from Webpay once it resolves the financial authorization
     *
     * @param string $token
     * @return mixed
     */
    public function getTransactionResult($token)
    {

        try {

            $getTransactionResult = new Fluent([
                'tokenInput' => $token,
            ]);

            // Perform the transaction
            $getTransactionResultResponse = $this->performGetTransactionResult($getTransactionResult);

            if ($this->validate()) {

                // Acknowledge the Transaction before returning the results
                if ($this->acknowledgeTransaction($token)) {

                    return $getTransactionResultResponse->return;

                } else {

                    $error['error'] = 'Error validando conexión a Webpay (Verifica que la información del certificado sea correcta)';
                    $error['detail'] = 'No se pudo completar la conexiónn con Webpay';
                }
            }
        } catch (Exception $e) {

            $error['error'] = 'Error conectando a Webpay (Verificar que la informaci&oacute;n del certificado sea correcta)';

            $replaceArray = ['<!--' => '', '-->' => ''];
            $error['detail'] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());
        }
    }

}
