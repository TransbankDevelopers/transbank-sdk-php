<?php


namespace Transbank\Webpay\Oneclick;


use Transbank\Webpay\Oneclick;
use Transbank\Webpay\Oneclick\Exceptions\AuthorizeMallTransactionException;
use Transbank\Webpay\Oneclick\Exceptions\MallRefundTransactionException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionStatusException;

class MallTransaction
{
    const AUTHORIZE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.0/transactions';
    const TRANSACTION_STATUS_ENDPONT = 'rswebpaytransaction/api/oneclick/v1.0/transactions/$BUYORDER$';
    const TRANSACTION_REFUND_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.0/transactions/$BUYORDER$/refunds';

    public static function getCommerceIdentifier($options){
        if ($options == null) {
            $commerceCode = Oneclick::getCommerceCode();
            $apiKey = Oneclick::getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl($options->getIntegrationType());
        }
        return array(
            $commerceCode, 
            $apiKey, 
            $baseUrl,
        );
    }

    public static function authorize(
        $userName,
        $tbkUser,
        $parentBuyOrder,
        $details,
        $options = null
    ) {

        list($commerceCode, $apiKey, $baseUrl) = MallTransaction::getCommerceIdentifier($options);

        $http = Oneclick::getHttpClient();
        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $payload = json_encode([
            "username" => $userName,
            "tbk_user" => $tbkUser,
            "buy_order" => $parentBuyOrder,
            "details" => $details
        ]);

        $httpResponse = $http->post($baseUrl,
            self::AUTHORIZE_TRANSACTION_ENDPOINT,
            $payload,
            ['headers' => $headers]
        );

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode)";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new AuthorizeMallTransactionException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);
        $authorizeTransactionResponse = new AuthorizeMallTransactionResponse($responseJson);

        return $authorizeTransactionResponse;
    }

    public static function getStatus($buyOrder, $options = null)
    {
        list($commerceCode, $apiKey, $baseUrl) = MallTransaction::getCommerceIdentifier($options);

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

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode)";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new MallTransactionStatusException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);
        $mallTransactionStatusResponse = new MallTransactionStatusResponse($responseJson);

        return $mallTransactionStatusResponse;
    }

    public static function refund($buyOrder, $childCommerceCode, $childBuyOrder, $amount, $options = null)
    {
        list($commerceCode, $apiKey, $baseUrl) = MallTransaction::getCommerceIdentifier($options);

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

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode)";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new MallRefundTransactionException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);
        $mallRefundTransactionResponse = new MallRefundTransactionResponse($responseJson);

        return $mallRefundTransactionResponse;
    }
}
