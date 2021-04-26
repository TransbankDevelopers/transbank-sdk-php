<?php

/**
 * Class Inscription.
 *
 * @category
 */

namespace Transbank\Patpass\PatpassComercio;

use GuzzleHttp\Exception\GuzzleException;
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
     *
     * @throws InscriptionStartException
     * @throws GuzzleException
     *
     * @return InscriptionStartResponse
     */
    public function start(
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
        $city
    ) {
        $payload = [
            'url'             => $url,
            'nombre'          => $name,
            'pApellido'       => $lastName,
            'sApellido'       => $secondLastName,
            'rut'             => $rut,
            'serviceId'       => $serviceId,
            'finalUrl'        => $finalUrl,
            'commerceCode'    => $this->getOptions()->getCommerceCode(),
            'montoMaximo'     => $maxAmount,
            'telefonoFijo'    => $phone,
            'telefonoCelular' => $cellPhone,
            'nombrePatPass'   => $patpassName,
            'correoPersona'   => $personEmail,
            'correoComercio'  => $commerceEmail,
            'direccion'       => $address,
            'ciudad'          => $city,
        ];
        $endpoint = static::INSCRIPTION_START_ENDPOINT;

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
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
     * @throws GuzzleException
     *
     * @return InscriptionStatusResponse
     */
    public function status($token)
    {
        $payload = [
            'token' => $token,
        ];

        $endpoint = str_replace('{token}', $token, static::INSCRIPTION_STATUS_ENDPOINT);

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw InscriptionStatusException::raise($exception);
        }

        return new InscriptionStatusResponse($response);
    }

    public static function getDefaultOptions()
    {
        return Options::forIntegration(PatpassComercio::DEFAULT_COMMERCE_CODE, PatpassComercio::DEFAULT_API_KEY);
    }

    public static function getGlobalOptions()
    {
        return PatpassComercio::getOptions();
    }
}
