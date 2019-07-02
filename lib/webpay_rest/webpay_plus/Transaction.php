<?php

namespace Transbank\Webpay\WebpayPlus;

use Transbank\Webpay\Exceptions\TransactionCommitException;
use Transbank\Webpay\Exceptions\TransactionCreateException;
use Transbank\Webpay\Exceptions\TransactionStatusException;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Mall\TransactionRefundException;

class Transaction
{

    /**
     * Path used for the 'create' endpoint
     */
    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions';

    const COMMIT_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions';

    const REFUND_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/refund';

    const GET_TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$';


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
            [],
            ['headers' => $headers]
        );

        if (!$httpResponse) {
            throw new TransactionCommitException('Could not obtain a response from the service', -1);
        }

        $responseJson = json_decode($httpResponse, true);

        if (array_key_exists("error_message", $responseJson)) {
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

        $payload = [
            "token" => $token,
            "amount" => $amount
        ];

        $url = str_replace('$TOKEN$', $token, self::REFUND_TRANSACTION_ENDPOINT);

        $http = WebpayPlus::getHttpClient();
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


        if (!$httpResponse) {
            throw new TransactionStatusException('Could not obtain a response from the service', -1);
        }

        $responseJson = json_decode($httpResponse, true);

        if (array_key_exists("error_message", $responseJson)) {
            throw new TransactionStatusException($responseJson['error_message']);
        }

        $transactionStatusResponse = new TransactionStatusResponse($responseJson);

        return $transactionStatusResponse;
    }

    public static function refundMall($token, $buyOrder, $commerceCode, $amount, $options = null)
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

        $url = str_replace('$TOKEN$', $token,
            \Transbank\Webpay\WebpayPlus\Mall\Transaction::REFUND_TRANSACTION_ENDPOINT);

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

}