<?php

namespace Transbank\Webpay\WebpayPlus;

use Transbank\Webpay\Exceptions\TransactionCaptureException;
use Transbank\Webpay\Exceptions\TransactionCommitException;
use Transbank\Webpay\Exceptions\TransactionCreateException;
use Transbank\Webpay\Exceptions\TransactionRefundException;
use Transbank\Webpay\Exceptions\TransactionStatusException;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;

class Transaction
{

    /**
     * Path used for the 'create' endpoint
     */
    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions';

    const COMMIT_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions';

    const REFUND_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/refunds';

    const GET_TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$';

    const CAPTURE_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/capture';

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
            "amount" => $amount,
            "return_url" => $returnUrl
        ]);

        $http = WebpayPlus::getHttpClient();

        $httpResponse = $http->post($baseUrl,
            self::CREATE_TRANSACTION_ENDPOINT,
            $payload,
            ['headers' => $headers]
        );

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new TransactionCreateException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);
        if (isset($responseJson["error_message"])) {
            throw new TransactionCreateException($responseJson['error_message']);
        }

        $transactionCreateResponse = new TransactionCreateResponse($responseJson);

        return $transactionCreateResponse;
    }

    public static function commit($token, $options = null)
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

        $http = WebpayPlus::getHttpClient();
        $httpResponse = $http->put($baseUrl,
            self::COMMIT_TRANSACTION_ENDPOINT . "/" . $token,
            null,
            ['headers' => $headers]
        );

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new TransactionCommitException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        if (isset($responseJson["error_message"])) {
            throw new TransactionCommitException($responseJson['error_message']);
        }

        $transactionCommitResponse = new TransactionCommitResponse($responseJson);

        return $transactionCommitResponse;
    }

    public static function refund($token, $amount, $options = null)
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
            "amount" => $amount
        ]);

        $url = str_replace('$TOKEN$', $token, self::REFUND_TRANSACTION_ENDPOINT);

        $http = WebpayPlus::getHttpClient();
        $httpResponse = $http->post($baseUrl,
            $url,
            $payload,
            ['headers' => $headers]
        );

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new TransactionRefundException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        if (isset($responseJson["error_message"])) {
            throw new TransactionRefundException($responseJson['error_message']);
        }

        $transactionRefundResponse = new TransactionRefundResponse($responseJson);

        return $transactionRefundResponse;
    }

    public static function getStatus($token, $options = null)
    {
        $url = str_replace('$TOKEN$', $token, self::GET_TRANSACTION_STATUS_ENDPOINT);
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

        $http = WebpayPlus::getHttpClient();
        $httpResponse = $http->get($baseUrl,
            $url,
            ['headers' => $headers]);


        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new TransactionStatusException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        if (isset($responseJson["error_message"])) {
            throw new TransactionStatusException($responseJson['error_message']);
        }

        $transactionStatusResponse = new TransactionStatusResponse($responseJson);

        return $transactionStatusResponse;
    }

    public static function createMall(
        $buyOrder,
        $sessionId,
        $returnUrl,
        $details,
        $options = null
    ) {
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

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $httpCode = $httpResponse->getStatusCode();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new TransactionCreateException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);
        if (isset($responseJson["error_message"])) {
            throw new TransactionCreateException($responseJson['error_message']);
        }

        $json = json_decode($httpResponse->getBody(), true);

        $transactionCreateResponse = new TransactionCreateResponse($json);

        return $transactionCreateResponse;
    }

    public static function commitMall($token, $options = null)
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

        $http = WebpayPlus::getHttpClient();
        $httpResponse = $http->put($baseUrl,
            self::COMMIT_TRANSACTION_ENDPOINT . "/" . $token,
            null,
            ['headers' => $headers]
        );

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new TransactionCommitException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        if (isset($responseJson["error_message"])) {
            throw new TransactionCommitException($responseJson['error_message']);
        }

        $transactionCommitMallResponse = new MallTransactionCommitResponse($responseJson);

        return $transactionCommitMallResponse;
    }

    public static function refundMall($token, $buyOrder, $childCommerceCode, $amount, $options = null)
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
                "buy_order" => $buyOrder,
                "commerce_code" => $childCommerceCode,
                "amount" => $amount
            ]);

        $http = WebpayPlus::getHttpClient();

        $url = str_replace('$TOKEN$', $token,
            self::REFUND_TRANSACTION_ENDPOINT);

        $httpResponse = $http->post($baseUrl,
            $url,
            $payload,
            ['headers' => $headers]
        );

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new TransactionRefundException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        if (isset($responseJson["error_message"])) {
            throw new TransactionRefundException($responseJson['error_message']);
        }

        $transactionRefundResponse = new TransactionRefundResponse($responseJson);

        return $transactionRefundResponse;
    }

    public static function getMallStatus($token, $options = null)
    {
        $url = str_replace('$TOKEN$', $token, self::GET_TRANSACTION_STATUS_ENDPOINT);
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

        $http = WebpayPlus::getHttpClient();
        $httpResponse = $http->get($baseUrl,
            $url,
            ['headers' => $headers]);


        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }
            throw new TransactionStatusException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        if (isset($responseJson["error_message"])) {
            throw new TransactionStatusException($responseJson['error_message']);
        }

        $transactionMallStatusResponse = new TransactionMallStatusResponse($responseJson);

        return $transactionMallStatusResponse;
    }

    public static function capture($token, $buyOrder, $authorizationCode, $captureAmount, $options = null)
    {
        $url = str_replace('$TOKEN$', $token, self::CAPTURE_ENDPOINT);
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
            "authorization_code" => $authorizationCode,
            "capture_amount" => $captureAmount
        ]);

        $http = WebpayPlus::getHttpClient();
        $httpResponse = $http->put($baseUrl,
            $url,
            $payload,
            ['headers' => $headers]);

        $httpCode = $httpResponse->getStatusCode();
        if ($httpCode != 200 && $httpCode != 204) {
            $reason = $httpResponse->getReasonPhrase();
            $message = "Could not obtain a response from the service: $reason (HTTP code $httpCode )";
            $body = json_decode($httpResponse->getBody(), true);

            if (isset($body["error_message"])) {
                $tbkErrorMessage = $body["error_message"];
                $message = "$message. Details: $tbkErrorMessage";
            }

            throw new TransactionCaptureException($message, -1);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        if (isset($responseJson["error_message"])) {
            throw new TransactionCaptureException($responseJson['error_message']);
        }

        $transactionCaptureResponse = new TransactionCaptureResponse($responseJson);

        return $transactionCaptureResponse;
    }

}
