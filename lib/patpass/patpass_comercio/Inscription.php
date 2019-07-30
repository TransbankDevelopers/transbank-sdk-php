<?php

/**
 * Class inscription
 *
 * @category
 * @package Transbank\PatPass\Commerce
 *
 */

namespace Transbank\PatPass\PatpassComercio;

use Transbank\Patpass\PatpassComercio;
use Transbank\PatPass\Exceptions\InscriptionStartException;
use Transbank\PatPass\Exceptions\InscriptionFinishException;


class Inscription
{
    const INSCRIPTION_START_ENDPOINT = 'restpatpass/v1/services/patInscription';
    const INSCRIPTION_FINISH_ENDPOINT = 'restpatpass/v1/services/patInscription/$TOKEN$';

    public static function start(
        $returnUrl,
        $name,
        $fLastname,
        $lLastname,
        $rut,
        $serviceId,
        $finalUrl,
        $maxAmount,
        $phoneNumber,
        $mobileNumber,
        $patpassName,
        $userEmail,
        $commerceEmail,
        $address,
        $city,
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
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];
        $payload = json_encode([
            "url" => $returnUrl,
            "nombre" => $name,
            "pApellido" => $fLastname,
            "sApellido" => $lLastname,
            "rut" => $rut,
            "serviceId" => $serviceId,
            "finalUrl" => $finalUrl,
            "commerceCode" => $commerceCode,
            "montoMaximo" => $maxAmount,
            "telefonoFijo" => $phoneNumber,
            "telefonoCelular" => $mobileNumber,
            "nombrePatPass" => $patpassName,
            "correoPersona" => $userEmail,
            "correoComercio" => $commerceEmail,
            "direccion" => $address,
            "ciudad" => $city
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

            throw new InscriptionStartException($message, -1);
        }
        $responseJson = json_decode($httpResponse->getBody(), true);
        $inscriptionStartResponse = new InscriptionStartResponse($responseJson);
        return $inscriptionStartResponse;

    }
}
