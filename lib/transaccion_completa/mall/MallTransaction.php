<?php

/**
 * Class MallTransaction
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;

use Transbank\TransaccionCompleta\Exceptions\MallTransactionCommitException;
use Transbank\TransaccionCompleta\Exceptions\MallTransactionCreateException;
use Transbank\TransaccionCompleta\Exceptions\MallTransactionInstallmentsException;
use Transbank\TransaccionCompleta\Exceptions\MallTransactionRefundException;
use Transbank\TransaccionCompleta\Exceptions\MallTransactionStatusException;

class MallTransaction
{
    const CREATE_TRANSACTION_ENDPOINT  = '/rswebpaytransaction/api/webpay/v1.0/transactions';
    const INSTALLMENTS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/installments';
    const COMMIT_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$';
    const REFUND_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/refunds';
    const STATUS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$';

    public static function create(
        $buyOrder,
        $sessionId,
        $cardNumber,
        $cardExpirationDate,
        $details,
        $options = null
    ) {
        if ($options == null) {
            $commerceCode = MallTransaccionCompleta::getCommerceCode();
            $apiKey = MallTransaccionCompleta::getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $payload = json_encode([
           "buy_order" => $buyOrder,
            "session_id" => $sessionId,
            "card_number" => $cardNumber,
            "card_expiration_date" => $cardExpirationDate,
            "details" => $details
        ]);

        $http = MallTransaccionCompleta::getHttpClient();

        $httpResponse = $http->post(
            $baseUrl,
            self::CREATE_TRANSACTION_ENDPOINT,
            $payload,
            [ 'headers' => $headers ]
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

            throw new MallTransactionCreateException($message, $httpCode);
        }
        $responseJson = json_decode($httpResponse->getBody(), true);

        $MallTransactionCreateResponse = new MallTransactionCreateResponse($responseJson);

        return $MallTransactionCreateResponse;
    }

    public static function installments(
        $token,
        $details,
        $options = null
    ) {
        if ($options == null) {
            $commerceCode = MallTransaccionCompleta::getCommerceCode();
            $apiKey = MallTransaccionCompleta::getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $url = str_replace('$TOKEN$', $token, self::INSTALLMENTS_TRANSACTION_ENDPOINT);
        $http = MallTransaccionCompleta::getHttpClient();

        $resp = array_map(function ($det) use ($baseUrl, $url, $headers, $http) {
            $payload = json_encode([
                "commerce_code" => $det["commerce_code"],
                "buy_order" => $det["buy_order"],
                "installments_number" => $det["installments_number"],
            ]);
            $httpResponse = $http->post(
                $baseUrl,
                $url,
                $payload,
                [ 'headers' => $headers ]
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

                throw new MallTransactionInstallmentsException($message, $httpCode);
            }

            $responseJson = json_decode($httpResponse->getBody(), true);
            $mallTransactionInstallmentsResponse = new MallTransactionInstallmentsResponse($responseJson);

            return $mallTransactionInstallmentsResponse;
        }, $details);

        return $resp;
    }

    public static function commit(
        $token,
        $details,
        $options = null
    ) {
        if ($options == null) {
            $commerceCode = MallTransaccionCompleta::getCommerceCode();
            $apiKey = MallTransaccionCompleta::getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $url = str_replace('$TOKEN$', $token, self::COMMIT_TRANSACTION_ENDPOINT);

        $payload = json_encode([
           "details" => $details
        ]);
        $http = MallTransaccionCompleta::getHttpClient();

        $httpResponse = $http->put(
            $baseUrl,
            $url,
            $payload,
            [ 'headers' => $headers ]
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

            throw new MallTransactionCommitException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $mallTransactionCommitResponse = new MallTransactionCommitResponse($responseJson);

        return $mallTransactionCommitResponse;
    }

    public static function refund(
        $token,
        $buyOrder,
        $commerceCodeChild,
        $amount,
        $options = null
    ) {
        if ($options == null) {
            $commerceCode = MallTransaccionCompleta::getCommerceCode();
            $apiKey = MallTransaccionCompleta::getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];
        $url = str_replace('$TOKEN$', $token, self::REFUND_TRANSACTION_ENDPOINT);

        $payload = json_encode([
            "buy_order" => $buyOrder,
            "commerce_code" => $commerceCodeChild,
            "amount" => $amount
        ]);

        $http = MallTransaccionCompleta::getHttpClient();

        $httpResponse = $http->post(
            $baseUrl,
            $url,
            $payload,
            [ 'headers' => $headers ]
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

            throw new MallTransactionRefundException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $mallTransactionRefundResponse = new MallTransactionRefundResponse($responseJson);

        return $mallTransactionRefundResponse;
    }

    public static function getStatus(
        $token,
        $options = null
    ) {
        if ($options == null) {
            $commerceCode = MallTransaccionCompleta::getCommerceCode();
            $apiKey = MallTransaccionCompleta::getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = MallTransaccionCompleta::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];
        $url = str_replace('$TOKEN$', $token, self::STATUS_TRANSACTION_ENDPOINT);

        $http = MallTransaccionCompleta::getHttpClient();

        $httpResponse = $http->get(
            $baseUrl,
            $url,
            [ 'headers' => $headers ]
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

        $transactionStatusResponse = new MallTransactionStatusResponse($responseJson);

        return $transactionStatusResponse;
    }
}
