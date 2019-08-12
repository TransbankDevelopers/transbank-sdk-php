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

    /**
     * @param $commerceCode
     * @return bool
     */
    private function validateChild($commerceCode)
    {
        $childlist = MallTransaccionCompleta::getChildCommerceCode();
        if (in_array($commerceCode, $childlist))
        {
            return true;
        }
        return false;
    }

    public static function create(
        $buyOrder,
        $sessionId,
        $cardNumber,
        $cardExpirationDate,
        $details,
        $options = null
    )
    {
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
            "sesion_id" => $sessionId,
            "card_number" => $cardNumber,
            "card_expiration_date" => $cardExpirationDate,
            "details" => $details
        ]);

        foreach ($details as $detail) {
            if (!(new MallTransaction)->validateChild($detail["commerce_code"])) {
                $message = "Child commerce code is not valid for this parent";
                $httpCode = 401;
                throw new MallTransactionCreateException($message, $httpCode);
            }
        }

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
        $commerceCodeChild,
        $buyOrder,
        $installmentsNumber,
        $options = null
    )
    {
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

        $payload = json_encode([
           "commerce_code" => $commerceCodeChild,
           "buy_order" => $buyOrder,
           "installments_number" => $installmentsNumber,
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

            throw new MallTransactionInstallmentsException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $mallTransactionInstallmentsResponse = new MallTransactionInstallmentsResponse($responseJson);

        return $mallTransactionInstallmentsResponse;
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

        foreach ($details as $detail) {
            if (!(new MallTransaction)->validateChild($detail["commerce_code"])) {
                $message = "Child commerce code is not valid for this parent";
                $httpCode = 401;
                throw new MallTransactionCommitException($message, $httpCode);
            }
            if (isset($detail["id_query_installments"]) == false) {
                $message = "There is not installments id in this commerce";
                $httpCode = 401;
                throw new MallTransactionCommitException($message, $httpCode);
            }
        }

        $payload = json_encode([
           "details"
        ]);

        $http = MallTransaccionCompleta::getHttpClient();

        $httpResponse = $http->put(
            $baseUrl.
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
    )
    {
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

        if (!(new MallTransaction)->validateChild($commerceCodeChild)) {
            $message = "Child commerce code is not valid for this parent";
            $httpCode = 401;
            throw new MallTransactionStatusException($message, $httpCode);
        }

        $payload = json_encode([
            "buy_order" => $buyOrder,
            "commerce_code" => $commerceCode,
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
    )
    {
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
