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
use Transbank\TransaccionCompleta\Exceptions\InscriptionStartException;
use Transbank\TransaccionCompleta\Exceptions\InscriptionFinishException;

class Inscription
{
    const INSCRIPTION_START_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/inscriptions';
    const INSCRIPTION_FINISH_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/inscriptions/$TOKEN$';

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

    public static function start(
        $userName,
        $email,
        $responseUrl,
        $options = null
    )
    {
        list($commerceCode, $apiKey, $baseUrl) = Inscription::getCommerceIdentifier($options);
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

        $inscriptionStartResponse = new InscriptionStartResponse($responseJson);
        return $inscriptionStartResponse;
    }

    public static function finish($token, $options = null)
    {
        list($commerceCode, $apiKey, $baseUrl) = Inscription::getCommerceIdentifier($options);

        $http = TransaccionCompleta::getHttpClient();
        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $url = str_replace('$TOKEN$', $token, self::INSCRIPTION_FINISH_ENDPOINT);

        $httpResponse = $http->put($baseUrl,
            $url,
            null,
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
            throw new InscriptionFinishException($message, $httpCode);
        }

        $responseJson = json_decode($httpResponse->getBody(), true);

        $inscriptionFinishResponse = new InscriptionFinishResponse($responseJson);

        return $inscriptionFinishResponse;
    }

}
