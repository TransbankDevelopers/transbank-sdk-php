<?php

namespace Transbank\Webpay\Transactions;

use Exception;
use Transbank\Helpers\Fluent;

/**
 * TRANSACCIÓN ANULACIÓN:
 * Este método permite a todo comercio habilitado anular una transacción que fue generada en
 * plataforma Webpay 3G. El método contempla anular total o parcialmente una transacción.
 */
class WebpayNullify extends Transaction
{

    /**
     * URL for the environment
     *
     * @var array
     */
    protected static $WSDL_URL_NORMAL = [
        'integration'   => 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl',
        'production'    => 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl',
    ];

    /**
     * Filename to include into the Result Codes array
     *
     * @var string
     */
    protected $resultCodesName = 'PlusCapture';

    /**
     * Class Map to require
     *
     * @var string
     */
    protected $classMapName = 'nullify';


    /** Método que permite anular una transacción de pago Webpay */

    /**
     * Nulls a Transaction in Webpay
     *
     * @param string $authorizationCode
     * @param float $authorizedAmount
     * @param string $buyOrder
     * @param float $nullifyAmount
     * @param int $commerceCode
     * @return mixed
     */
    public function nullify($authorizationCode, $authorizedAmount, $buyOrder, $nullifyAmount, $commerceCode)
    {

        try {
            $nullificationInput = new Fluent([
                // Transaction Code or Capture Authorization Code
                'authorizationCode' => $authorizationCode,
                // Authorized Transaction amount to null (substract), or full Capture Amount
                'authorizedAmount' => $authorizedAmount,
                'buyOrder' => $buyOrder,
                'commerceId' => floatval($commerceCode ? $commerceCode : $this->config->getCommerceCode()),
                'nullifyAmount' => $nullifyAmount
            ]);


            $nullifyResponse = $this->performNullify($nullificationInput);

            // Return the result if the validation passes
            if ($this->validate()) {
                return $nullifyResponse->return;
            } else {

                $error['error'] = "No se pudo completar la conexión con Webpay";
            }

        } catch (Exception $e) {

            $error["error"] = "Error conectando a Webpay (Verifica que la información del certificado sea correcta)";

            $replaceArray = ['<!--' => '', '-->' => ''];
            $error["detail"] = str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage());

        }

        return $error;

    }

    /**
     * Performs the Nullify on Webpay
     *
     * @param $transaction
     * @return mixed
     */
    protected function performNullify($transaction)
    {
        return $this->soapClient->nullify([
            'nullificationInput' => $transaction
        ]);
    }

}
