<?php

namespace Transbank\Webpay\Transactions;

use Exception;
use Transbank\Helpers\Fluent;

/**
 * Class WebpayCapture
 *
 * This class allows the commerce to capture an transaction made through Webpay
 *
 * @package Transbank\Webpay\Transactions
 */
class WebpayCapture extends Transaction
{
    /**
     * URL for the environment
     *
     * @var array
     */
    protected static $WSDL_URL_NORMAL = [
        'integration' => 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl',
        'production' => 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl',
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
    protected $classMapName = 'capture';

    /**
     * Captures the transaction
     *
     * @param string $authorizationCode
     * @param string|float|int $captureAmount
     * @param string $buyOrder
     * @return mixed
     */
    public function capture($authorizationCode, $captureAmount, $buyOrder)
    {
        try {
            $captureInput = new Fluent([
                // Authorization code for the Transaction to capture
                'authorizationCode' => $authorizationCode,
                // Buy Order of the Transaction to capture
                'buyOrder' => $buyOrder,
                // Amount to capture
                'captureAmount' => $captureAmount,
                // Commerce Code or Mall Commerce Code who did the target Transaction
                'commerceId' => floatval($this->config->getCommerceCode()),
            ]);

            // Perform the capture with the data
            $captureResponse = $this->performCapture($captureInput);

            // If the validation is successful, return the results
            if ($this->validate()) {
                return $captureResponse->return;
            } else {
                $error['error'] = 'No se pudo completar la conexión con Webpay';
            }
        } catch (Exception $e) {

            $replaceArray = [
                '<!--' => '',
                '-->' => ''
            ];

            $error = [
                'error' => 'Error al conectar con Webpay. Verifica que la información del certificado sea correcta.',
                'detail' => str_replace(array_keys($replaceArray), array_values($replaceArray), $e->getMessage())
            ];

        }

        return $error;
    }

    /**
     * Performs the Webpay Capture operation
     *
     * @param Fluent $capture
     * @return object
     */
    protected function performCapture(Fluent $capture)
    {
        return $this->soapClient->capture([
            'captureInput' => $capture
        ]);
    }

}
