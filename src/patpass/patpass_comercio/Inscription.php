<?php

/**
 * Class Inscription
 *
 * @category
 * @package Transbank\Patpass\PatpassComercio
 *
 */


namespace Transbank\Patpass\PatpassComercio;

use Transbank\Patpass\PatpassComercio;
use Transbank\Patpass\PatpassComercio\Exceptions\InscriptionStartException;
use Transbank\Patpass\PatpassComercio\Exceptions\InscriptionStatusException;

class Inscription
{
    const INSCRIPTION_START_ENDPOINT = 'restpatpass/v1/services/patInscription';
    const INSCRIPTION_STATUS_ENDPOINT = 'restpatpass/v1/services/status';

    public static function start(
        $url,
        $name,
        $lastName,
        $secondLastName,
        $rut,
        $serviceId,
        $finalUrl,
        $maxAmount,
        $phone,
        $cellPhone,
        $patpassName,
        $personEmail,
        $commerceEmail,
        $address,
        $city,
        $options = null
    ) {
        if ($options == null) {
            $commerceCode = PatpassComercio::getCommerceCode();
            $apiKey = PatpassComercio::getApiKey();
            $baseUrl = PatpassComercio::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = PatpassComercio::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $http = PatpassComercio::getHttpClient();

        $headers = [
            "commercecode" => $commerceCode,
            "Authorization" => $apiKey
        ];

        $payload = json_encode([
            "url" => $url,
            "nombre" => $name,
            "pApellido" => $lastName,
            "sApellido" => $secondLastName,
            "rut" => $rut,
            "serviceId" => $serviceId,
            "finalUrl" => $finalUrl,
            "commerceCode" => $commerceCode,
            "montoMaximo" => $maxAmount,
            "telefonoFijo" => $phone,
            "telefonoCelular" => $cellPhone,
            "nombrePatPass" => $patpassName,
            "correoPersona" => $personEmail,
            "correoComercio" => $commerceEmail,
            "direccion" => $address,
            "ciudad" => $city
        ]);
        $httpResponse = $http->post(
            $baseUrl,
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

    public static function status($token, $options = null)
    {
        if ($options == null) {
            $commerceCode = PatpassComercio::getCommerceCode();
            $apiKey = PatpassComercio::getApiKey();
            $baseUrl = PatpassComercio::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = PatpassComercio::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $http = PatpassComercio::getHttpClient();
        $headers = [
            "commercecode" => $commerceCode,
            "Authorization" => $apiKey
        ];

        $payload = json_encode([
            "token" => $token
        ]);

        $httpResponse = $http->post(
            $baseUrl,
            self::INSCRIPTION_STATUS_ENDPOINT,
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
            throw new InscriptionStatusException($message, $httpCode);
        }
        $responseJson = json_decode($httpResponse->getBody(), true);
        $inscriptionStatusResponse = new InscriptionStatusResponse($responseJson);

        return $inscriptionStatusResponse;
    }
}
