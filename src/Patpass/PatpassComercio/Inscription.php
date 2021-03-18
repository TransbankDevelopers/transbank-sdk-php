<?php

/**
 * Class Inscription.
 *
 * @category
 */

namespace Transbank\Patpass\PatpassComercio;

use Transbank\Patpass\PatpassComercio;
use Transbank\Patpass\PatpassComercio\Exceptions\InscriptionStartException;
use Transbank\Patpass\PatpassComercio\Exceptions\InscriptionStatusException;
use Transbank\Patpass\PatpassComercio\Responses\InscriptionStartResponse;
use Transbank\Patpass\PatpassComercio\Responses\InscriptionStatusResponse;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;

class Inscription
{
    use InteractsWithWebpayApi;
    const INSCRIPTION_START_ENDPOINT = 'restpatpass/v1/services/patInscription';
    const INSCRIPTION_STATUS_ENDPOINT = 'restpatpass/v1/services/status';

    /**
     * @param Options $options
     *
     * @return array
     */
    public static function getHeaders(Options $options)
    {
        return [
            'commercecode'  => $options->getCommerceCode(),
            'Authorization' => $options->getApiKey(),
        ];
    }

    /**
     * @param $url
     * @param $name
     * @param $lastName
     * @param $secondLastName
     * @param $rut
     * @param $serviceId
     * @param $finalUrl
     * @param $maxAmount
     * @param $phone
     * @param $cellPhone
     * @param $patpassName
     * @param $personEmail
     * @param $commerceEmail
     * @param $address
     * @param $city
     * @param null $options
     *
     * @throws InscriptionStartException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return InscriptionStartResponse
     */
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
        $options = PatpassComercio::getDefaultOptions($options);

        $payload = [
            'url'             => $url,
            'nombre'          => $name,
            'pApellido'       => $lastName,
            'sApellido'       => $secondLastName,
            'rut'             => $rut,
            'serviceId'       => $serviceId,
            'finalUrl'        => $finalUrl,
            'commerceCode'    => $options->getCommerceCode(),
            'montoMaximo'     => $maxAmount,
            'telefonoFijo'    => $phone,
            'telefonoCelular' => $cellPhone,
            'nombrePatPass'   => $patpassName,
            'correoPersona'   => $personEmail,
            'correoComercio'  => $commerceEmail,
            'direccion'       => $address,
            'ciudad'          => $city,
        ];
        $endpoint = self::INSCRIPTION_START_ENDPOINT;

        try {
            $response = static::request('POST', $endpoint, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw InscriptionStartException::raise($exception);
        }

        return new InscriptionStartResponse($response);
    }

    /**
     * @param $token
     * @param null $options
     *
     * @throws InscriptionStatusException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return InscriptionStatusResponse
     */
    public static function status($token, $options = null)
    {
        $options = PatpassComercio::getDefaultOptions($options);

        $payload = [
            'token' => $token,
        ];

        $endpoint = str_replace('{token}', $token, self::INSCRIPTION_STATUS_ENDPOINT);

        try {
            $response = static::request('POST', $endpoint, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw InscriptionStatusException::raise($exception);
        }

        return new InscriptionStatusResponse($response);
    }

    /**
     * @param $integrationEnvironment
     *
     * @return mixed|string
     */
    public static function getBaseUrl($integrationEnvironment)
    {
        return PatpassComercio::getIntegrationTypeUrl($integrationEnvironment);
    }
}
