<?php

/**
 * Class MallTransaction
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;

use Transbank\TransaccionCompleta;
use Transbank\TransaccionCompleta\Exceptions\MallTransactionCreateException;

class MallTransaction
{
    const CREATE_TRANSACTION_ENDPOINT  = '/rswebpaytransaction/api/webpay/v1.0/transactions';
    const INSTALLMENTS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/installments';
    const COMMIT_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$';
    const REFUND_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$/refunds';
    const STATUS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.0/transactions/$TOKEN$';

    private function validateChild($commerceCode)
    {
        $chillist = MallTransaccionCompleta::getChildCommerceCode();
        if (in_array($commerceCode, $chillist))
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
           "buy_order" => $buyOrder
        ]);

        foreach ($details as $detail) {
            if (!self::validateChild($detail["commerce_code"])) {
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

}
