<?php


namespace Transbank\Webpay\Oneclick;


use Transbank\Webpay\Exceptions\AuthorizeMallTransactionException;
use Transbank\Webpay\Exceptions\MallRefundTransactionException;
use Transbank\Webpay\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\Oneclick;

class MallTransaction
{

    const AUTHORIZE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.0/transactions';
    const TRANSACTION_STATUS_ENDPONT = 'rswebpaytransaction/api/oneclick/v1.0/transactions/$BUYORDER$';
    const TRANSACTION_REFUND_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.0/transactions/$BUYORDER$/refunds';

    public static function authorize($userName, $tbkUser, $parentBuyOrder, $details,
                                     $options = null)
    {

        if ($options == null) {
            $commerceCode = Oneclick::getCommerceCode();
            $apiKey = Oneclick::getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $http = Oneclick::getHttpClient();
        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $payload = json_encode([
            "username" => $userName,
            "tbk_user" => $tbkUser,
            "buy_order" => $parentBuyOrder,
            "details" => $details]);

        $httpResponse = $http->post($baseUrl,
            self::AUTHORIZE_TRANSACTION_ENDPOINT,
            $payload,
            ['headers' => $headers]
        );

        if ($httpResponse->getStatusCode() != 200) {
            $reason = $httpResponse->getReasonPhrase();
            $httpCode = $httpResponse->getStatusCode();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            throw new AuthorizeMallTransactionException($message, -1);
        }

        $responseJson = json_decode($httpResponse, true);
        if (array_key_exists('error_message', $responseJson)) {
            throw new AuthorizeMallTransactionException($responseJson['error_message']);
        }

        $authorizeTransactionResponse = new AuthorizeMallTransactionResponse($responseJson);

        return $authorizeTransactionResponse;
    }

    public static function getStatus($buyOrder, $options = null)
    {
        if ($options == null) {
            $commerceCode = Oneclick::getCommerceCode();
            $apiKey = Oneclick::getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $http = Oneclick::getHttpClient();
        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $url = str_replace('$BUYORDER$', $buyOrder, self::TRANSACTION_STATUS_ENDPONT);
        $httpResponse = $http->get($baseUrl,
            $url,
            ['headers' => $headers]
        );

        if ($httpResponse->getStatusCode() != 200) {
            $reason = $httpResponse->getReasonPhrase();
            $httpCode = $httpResponse->getStatusCode();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            throw new MallTransactionStatusException($message, -1);
        }

        $responseJson = json_decode($httpResponse, true);
        if (array_key_exists('error_message', $responseJson)) {
            throw new MallTransactionStatusException($responseJson['error_message']);
        }
        $mallTransactionStatusResponse = new MallTransactionStatusResponse($responseJson);

        return $mallTransactionStatusResponse;
    }

    public static function refund($buyOrder,$childCommerceCode, $childBuyOrder, $amount, $options = null)
    {
        if ($options == null) {
            $commerceCode = Oneclick::getCommerceCode();
            $apiKey = Oneclick::getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $http = Oneclick::getHttpClient();
        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $payload = json_encode([
            "detail_buy_order" => $childBuyOrder,
            "commerce_code" => $childCommerceCode,
            "amount" => $amount
        ]);

        $url = str_replace('$BUYORDER$', $buyOrder, self::TRANSACTION_REFUND_ENDPOINT);
        $httpResponse = $http->post($baseUrl,
            $url,
            $payload,
            ['headers' => $headers]
        );

        if ($httpResponse->getStatusCode() != 200) {
            $reason = $httpResponse->getReasonPhrase();
            $httpCode = $httpResponse->getStatusCode();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            throw new MallRefundTransactionException($message, -1);
        }

        $responseJson = json_decode($httpResponse, true);
        if (array_key_exists('error_message', $responseJson)) {
            throw new MallRefundTransactionException($responseJson['error_message']);
        }

        $mallRefundTransactionResponse = new MallRefundTransactionResponse($responseJson);

        return $mallRefundTransactionResponse;
    }

}
