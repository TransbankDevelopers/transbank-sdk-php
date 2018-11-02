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
    protected $resultCodesName = 'pluscapture';

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
            $response = $this->performCapture($captureInput);

            // If the validation is successful, return the results
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }
    }

    /**
     * Performs the Webpay Capture operation
     *
     * @param $capture
     * @return object
     */
    protected function performCapture($capture)
    {
        return $this->soapClient->capture([
            'captureInput' => $capture
        ]);
    }

}
