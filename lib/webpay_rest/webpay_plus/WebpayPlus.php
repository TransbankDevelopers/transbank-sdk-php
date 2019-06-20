<?php


namespace Transbank\Webpay;


use Transbank\Webpay\Exceptions\TransactionCreateException;
use Transbank\Utils\HttpClient;
use Transbank\Webpay\WebpayPlus\TransactionCreateResponse;

/**
 * Class WebpayPlus
 *
 * @package Transbank\Webpay
 *
 */
class WebpayPlus
{
    /**
     * BASE URL of Transbank's Webpay service
     */
    const BASE_URL = 'https://webpay3gint.transbank.cl/';
    /**
     * Path used for the 'create' endpoint
     */
    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions';
    /**
     * @var $options Options|null
     */
    private static $options = null;
    /**
     * @var $httpClient HttpClient|null
     */
    private static $httpClient = null;

    /**
     * @param string $buyOrder
     * @param string $sessionId
     * @param integer $amount
     * @param string $returnUrl
     * @param Options|null $options
     *
     * @return TransactionCreateResponse
     * @throws TransactionCreateException
     **
     */
    public static function create(
        $buyOrder,
        $sessionId,
        $amount,
        $returnUrl,
        $options = null
    ) {
        if ($options == null) {
            $options = self::getOptions();
        }

        $headers = [
            "Tbk-Api-Key-Id" => $options->getCommerceCode(),
            "Tbk-Api-Key-Secret" => $options->getApiKey()
        ];

        $payload = json_encode([
            "buy_order" => $buyOrder,
            "session_id" => $sessionId,
            "amount" => $amount,
            "return_url" => $returnUrl
        ]);


        $http = self::getHttpClient();


        $httpResponse = $http->post(self::BASE_URL,
            self::CREATE_TRANSACTION_ENDPOINT,
            $payload,
            ['headers' => $headers]
        );

        if (!$httpResponse) {
            throw new TransactionCreateException('Could not obtain a response from the service', -1);
        }

        $responseJson = json_decode($httpResponse, true);
        if (!$responseJson["token"] || !$responseJson['url']) {
            throw new TransactionCreateException($responseJson['error_message']);
        }

        $json = json_decode($httpResponse, true);

        $transactionCreateResponse = new TransactionCreateResponse($json);

        return $transactionCreateResponse;
    }


    /**
     * @return Options|null
     */
    private static function getOptions()
    {
        if (!isset(self::$options) || self::$options == null) {
            self::$options = Options::defaultConfig();
        }
        return self::$options;
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
