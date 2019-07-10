<?php


namespace Transbank\Webpay\Oneclick;


use Transbank\Webpay\Exceptions\InscriptionFinishException;
use Transbank\Webpay\Exceptions\InscriptionStartException;
use Transbank\Webpay\Oneclick;

class MallInscription
{

    const INSCRIPTION_START_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.0/inscriptions';
    const INSCRIPTION_FINISH_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.0/inscriptions/$TOKEN$';

    public static function start($userName, $email, $responseUrl, $options = null)
    {

        if ($options == null) {
            $commerceCode = Oneclick::getCommerceCode();
            $apiKey = Oneclick::getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = WebpayPlus::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $http = Oneclick::getHttpClient();
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

        if (!$httpResponse) {
            throw new InscriptionStartException('Could not obtain a response from the service', -1);
        }

        $responseJson = json_decode($httpResponse, true);
        if (isset($responseJson['error_message'])) {
            throw new InscriptionStartException($responseJson['error_message']);
        }
        $json = json_decode($httpResponse, true);

        $inscriptionStartResponse = new InscriptionStartResponse($json);

        return $inscriptionStartResponse;
    }


    public static function finish($token, $options = null)
    {
        if ($options == null) {
            $commerceCode = Oneclick::getCommerceCode();
            $apiKey = Oneclick::getApiKey();
            $baseUrl = Oneclick::getIntegrationTypeUrl();
        } else {
            $commerceCode = $options->getCommerceCode();
            $apiKey = $options->getApiKey();
            $baseUrl = WebpayPlus::getIntegrationTypeUrl($options->getIntegrationType());
        }

        $http = Oneclick::getHttpClient();
        $headers = [
            "Tbk-Api-Key-Id" => $commerceCode,
            "Tbk-Api-Key-Secret" => $apiKey
        ];

        $url = str_replace('$TOKEN$', $token, self::INSCRIPTION_FINISH_ENDPOINT);

        $httpResponse = $http->put($baseUrl,
            $url,
            [],
            ['headers' => $headers]
        );

        if (!$httpResponse) {
            throw new InscriptionFinishException('Could not obtain a response from the service', -1);
        }

        $responseJson = json_decode($httpResponse, true);
        if (isset($responseJson['error_message'])) {
            throw new InscriptionFinishException($responseJson['error_message']);
        }
        $json = json_decode($httpResponse, true);

        $inscriptionFinishResponse = new InscriptionFinishResponse($json);

        return $inscriptionFinishResponse;
    }
}
