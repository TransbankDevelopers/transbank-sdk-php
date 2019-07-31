<?php

/**
 * Class Inscription
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;

use Transbank\TransaccionCompleta;
use Transbank\TransaccionCompleta\InscriptionStartResponse;
use Transbank\TransaccionCompleta\Exceptions\InscriptionStartException;

class Inscription
{
    const INSCRIPTION_START_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/inscriptions';

    public static function start(
        $userName,
        $email,
        $responseUrl,
        $options = null
    )
    {
        if ($options == null) {
            $commerceCode = TransaccionCompleta::getCommerceCode();
            $apiKey = TransaccionCompleta::getApiKey();
            $baseUrl = TransaccionCompleta::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = TransaccionCompleta::getIntegrationTypeUrl($options->getIntegrationType());
        }
        $http = TransaccionCompleta::getHttpClient();
        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $payload = json_encode(["username" => $userName, "email" => $email, "response_url" => $responseUrl]);

        $httpResponse = $http->post($baseUrl,
            self::INSCRIPTION_START_ENDPOINT,
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
            throw new InscriptionStartException($message, $httpCode);
        }
        $responseJson = json_decode($httpResponse->getBody(), true);

        if (isset($responseJson["error_message"])) {
            throw new InscriptionStartException($responseJson['error_message']);
        }
        $inscriptionStartResponse = new InscriptionStartResponse($responseJson);
        return $inscriptionStartResponse;
    }

}
