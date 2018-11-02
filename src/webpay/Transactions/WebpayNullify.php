<?php

namespace Transbank\Webpay\Transactions;

use Exception;
use Transbank\Helpers\Fluent;

/**
 * Class WebpayNullify
 *
 * This class allows the commerce to nullify a Transaction, totally or parcially.
 *
 * @package Transbank\Webpay\Transactions
 */
class WebpayNullify extends Transaction
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
            $transaction = new Fluent([
                // Transaction Code or Capture Authorization Code
                'authorizationCode' => $authorizationCode,
                // Authorized Transaction amount to null (substract), or full Capture Amount
                'authorizedAmount' => $authorizedAmount,
                'buyOrder' => $buyOrder,
                'commerceId' => floatval($commerceCode ? $commerceCode : $this->config->getCommerceCode()),
                'nullifyAmount' => $nullifyAmount
            ]);


            $response = $this->performNullify($transaction);

            // If the validation is successful, return the results
            return $this->validate()
                ? $response->return
                : $this->returnValidationErrorArray();

        } catch (Exception $e) {
            return $this->returnConnectionErrorArray($e->getMessage());
        }

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
