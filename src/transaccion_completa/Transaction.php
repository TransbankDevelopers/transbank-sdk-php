<?php

/**
 * Class Transaction
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


use Transbank\TransaccionCompleta;
use Transbank\TransaccionCompleta\Exceptions\TransactionCreateException;
use Transbank\TransaccionCompleta\Exceptions\TransactionInstallmentsException;
use Transbank\TransaccionCompleta\Exceptions\TransactionCommitException;
use Transbank\TransaccionCompleta\Exceptions\TransactionRefundException;
use Transbank\TransaccionCompleta\Exceptions\TransactionStatusException;

class Transaction
{
    const CREATE_TRANSACTION_ENDPOINT  = '/rswebpaytransaction/api/webpay/v1.0/transactions';
    const INSTALLMENTS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/installments';
    const COMMIT_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$';
    const REFUND_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/refunds';
    const STATUS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$';

    public static function getCommerceIdentifier($options){
        if ($options == null) {
            $commerceCode = TransaccionCompleta::getCommerceCode();
            $apiKey = TransaccionCompleta::getApiKey();
            $baseUrl = TransaccionCompleta::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = TransaccionCompleta::getIntegrationTypeUrl($options->getIntegrationType());
        }
        return array(
            $commerceCode, 
            $apiKey, 
            $baseUrl,
        );
    }

    public static function create(
        $buyOrder,
        $sessionId,
        $amount,
        $cvv,
        $cardNumber,
        $cardExpirationDate,
        $options = null
    ) {
        list($commerceCode, $apiKey, $baseUrl) = Transaction::getCommerceIdentifier($options);

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $payload = json_encode([
            "buy_order" => $buyOrder,
            "session_id" => $sessionId,
            "amount" => $amount,
            "cvv" => $cvv,
            "card_number" => $cardNumber,
            "card_expiration_date" => $cardExpirationDate
        ]);

        $http = TransaccionCompleta::getHttpClient();

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

            throw new TransactionCreateException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $transactionCreateResponse = new TransactionCreateResponse($responseJson);

        return $transactionCreateResponse;
    }

    public static function installments(
        $token,
        $installmentsNumber,
        $options = null
    ) {
        list($commerceCode, $apiKey, $baseUrl) = Transaction::getCommerceIdentifier($options);

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];
        $url = str_replace('$TOKEN$', $token, self::INSTALLMENTS_TRANSACTION_ENDPOINT);

        $payload = json_encode([
            "installments_number" => $installmentsNumber
        ]);

        $http = TransaccionCompleta::getHttpClient();

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

            throw new TransactionInstallmentsException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $transactionInstallmentsResponse = new TransactionInstallmentsResponse($responseJson);

        return $transactionInstallmentsResponse;

    }

    public static function commit(
        $token,
        $idQueryInstallments,
        $deferredPeriodIndex,
        $gracePeriod,
        $options = null
    )
    {
        list($commerceCode, $apiKey, $baseUrl) = Transaction::getCommerceIdentifier($options);

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];
        $url = str_replace('$TOKEN$', $token, self::COMMIT_TRANSACTION_ENDPOINT);

        $payload = json_encode([
           "id_query_installments" => $idQueryInstallments,
           "deferred_period_index" => $deferredPeriodIndex,
           "grace_period" => $gracePeriod
        ]);

        $http = TransaccionCompleta::getHttpClient();

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

            throw new TransactionCommitException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $transactionCommitResponse = new TransactionCommitResponse($responseJson);

        return $transactionCommitResponse;
    }

    public static function refund(
        $token,
        $amount,
        $options = null
    ) {
        list($commerceCode, $apiKey, $baseUrl) = Transaction::getCommerceIdentifier($options);

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];
        $url = str_replace('$TOKEN$', $token, self::REFUND_TRANSACTION_ENDPOINT);

        $payload = json_encode([
            "amount" => $amount
        ]);

        $http = TransaccionCompleta::getHttpClient();

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

            throw new TransactionRefundException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $transactionRefundResponse = new TransactionRefundResponse($responseJson);

        return $transactionRefundResponse;

    }

    public static function getStatus(
        $token,
        $options = null
    ) {
        list($commerceCode, $apiKey, $baseUrl) = Transaction::getCommerceIdentifier($options);

        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];
        $url = str_replace('$TOKEN$', $token, self::STATUS_TRANSACTION_ENDPOINT);

        $http = TransaccionCompleta::getHttpClient();

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

            throw new TransactionStatusException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $transactionStatusResponse = new TransactionStatusResponse($responseJson);

        return $transactionStatusResponse;
    }

}
