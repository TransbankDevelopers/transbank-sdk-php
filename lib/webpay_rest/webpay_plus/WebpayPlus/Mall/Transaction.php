<?php


namespace Transbank\Webpay\WebpayPlus\Mall;


use Transbank\Webpay\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\TransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\TransactionRefundResponse;

class Transaction extends \Transbank\Webpay\WebpayPlus\Transaction
{

    public static function create(
        $buyOrder,
        $sessionId,
        $returnUrl,
        $details,
        $options = null)
    {
        if ($options == null) {
            $commerceCode = WebpayPlus::getCommerceCode();
            $apiKey = WebpayPlus::getApiKey();
            $baseUrl = WebpayPlus::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = WebpayPlus::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $payload = json_encode([
            "buy_order" => $buyOrder,
            "session_id" => $sessionId,
            "details" => $details,
            "return_url" => $returnUrl
        ]);

        $http = WebpayPlus::getHttpClient();

        $httpResponse = $http->post($baseUrl,
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


    public static function refund($token, $buyOrder, $commerceCode, $amount, $options = null)
    {
        if ($options == null) {
            $commerceCode = WebpayPlus::getCommerceCode();
            $apiKey = WebpayPlus::getApiKey();
            $baseUrl = WebpayPlus::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = WebpayPlus::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $payload = json_encode(
        [
         "token" => $token,
         "buy_order" => $buyOrder,
         "commerce_code" => $commerceCode
        ]);

        $http = WebpayPlus::getHttpClient();

        $url = str_replace('$TOKEN$', $token, self::REFUND_TRANSACTION_ENDPOINT);

        $httpResponse = $http->post($baseUrl,
            $url,
            $payload,
            ['headers' => $headers]
        );

        if (!$httpResponse) {
            throw new TransactionRefundException('Could not obtain a response from the service', -1);
        }

        $responseJson = json_decode($httpResponse, true);

        if (array_key_exists("error_message", $responseJson)) {
            throw new TransactionRefundException($responseJson['error_message']);
        }

        $transactionRefundResponse = new TransactionRefundResponse($responseJson);

        return $transactionRefundResponse;
    }

}
