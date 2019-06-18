<?php


namespace Transbank\Webpay;


use Transbank\Onepay\Exceptions\TransactionCreateException;
use Transbank\Utils\HttpClient;
use Transbank\Webpay\Plus\TransactionCreateResponse;

/**
 * Class WebPayPlus
 *
 * @package Transbank\Webpay
 */
class WebpayPlus
{
    const BASE_URL = 'https://servidorwebpay.cl/';
    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transaction';
    private static $configuration = null;
    private static $httpClient = null;

    /**
     * @param string $buyOrder
     * @param string $sessionId
     * @param integer $amount
     * @param string $returnUrl
     * @param \Transbank\Webpay\Plus\Configuration|null $configuration
     *
     * @return TransactionCreateResponse
     * @throws
     */
    public static function create(
        $buyOrder,
        $sessionId,
        $amount,
        $returnUrl,
        $configuration = null
    ) {
        if ($configuration == null) {
            $configuration = self::getConfiguration();
        }

        $headers = [
            "Tbk-Api-Key-Id" => $configuration->getCommerceCode(),
            "Tbk-Api-Key-Secret" => $configuration->getSharedSecret()
        ];

        $data_to_send = [
            "buy_order" => $buyOrder,
            "session_id" => $sessionId,
            "amount" => $amount,
            "return_url" => $returnUrl
        ];

        $http = self::getHttpClient();


        $httpResponse = $http->post(self::BASE_URL,
            self::CREATE_TRANSACTION_ENDPOINT,
            $data_to_send,
            ['headers' => $headers]);
        if (!$httpResponse) {
            throw new TransactionCreateException('Could not obtain a response from the service', -1);
        }

        $transactionCreateResponse = new TransactionCreateResponse($httpResponse);

        return $transactionCreateResponse;
    }


    /**
     * @return Plus\Configuration|null
     */
    private static function getConfiguration()
    {
        if (!isset(self::$configuration) || self::$configuration == null) {
            self::$configuration = \Transbank\Webpay\Plus\Configuration::defaultConfig();
        }
        return self::$configuration;
    }

    /**
     * @return HttpClient|null
     */
    private static function getHttpClient()
    {
        if (!isset(self::$httpClient) || self::$httpClient == null) {
            self::$httpClient = new HttpClient();
        }
        return self::$httpClient;
    }


}
