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
    const INSCRIPTION_FINISH_ENDPOINT = 'restpatpass/v1/services/patInscription/$TOKEN$';

    public static function start(
        $requestData,
        $options = null
    )
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
            "url" => isset($requestData['url']),
            "nombre" => isset($requestData['nombre']),
            "pApellido" => isset($requestData['pApellido']),
            "sApellido" => isset($requestData['sApellido']),
            "rut" => isset($requestData['rut']),
            "serviceId" => isset($requestData['serviceId']) ,
            "finalUrl" => isset($requestData['finalUrl']),
            "commerceCode" => isset($commerceCode),
            "montoMaximo" => isset($requestData['montoMaximo']),
            "telefonoFijo" => isset($requestData['telefonoFijo']),
            "telefonoCelular" => isset($requestData['telefonoCelular']),
            "nombrePatPass" => isset($requestData['nombrePatPass']),
            "correoPersona" => isset($requestData['correoPersona']),
            "correoComercio" => isset($requestData['correoComercio']),
            "direccion" => isset($requestData['direccion']),
            "ciudad" => isset($requestData['ciudad'])
        ]);
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

        $httpResponse = $http->put($baseUrl,
            self::INSCRIPTION_FINISH_ENDPOINT,
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

        $inscriptionFinishResponse = new InscriptionStatusResponse($httpCode);

        return $inscriptionFinishResponse;

    }
}
