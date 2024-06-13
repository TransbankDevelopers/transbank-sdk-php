<?php

/**
 * Class Inscription.
 *
 * @category
 */

namespace Transbank\Patpass\PatpassComercio;

use Transbank\Patpass\PatpassComercio\Exceptions\InscriptionStartException;
use Transbank\Patpass\PatpassComercio\Exceptions\InscriptionStatusException;
use Transbank\Patpass\PatpassComercio\Responses\InscriptionStartResponse;
use Transbank\Patpass\PatpassComercio\Responses\InscriptionStatusResponse;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Utils\RequestServiceTrait;
use Transbank\Contracts\RequestService;
use Transbank\Patpass\Options;

class Inscription
{
    use RequestServiceTrait;
    const INSCRIPTION_START_ENDPOINT = 'restpatpass/v1/services/patInscription';
    const INSCRIPTION_STATUS_ENDPOINT = 'restpatpass/v1/services/status';

    /**
     * @var Options
     */
    protected $options;

    /**
     * Transaction constructor.
     *
     * @param Options              $options
     * @param RequestService |null $requestService
     */
    public function __construct(
        Options $options,
        RequestService $requestService = null
    ) {
        $this->options = $options;

        $this->setRequestService($requestService !== null ? $requestService :
            new HttpClientRequestService());
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
     *
     * @throws InscriptionStartException
     * @throws GuzzleHttp\Exception\GuzzleException
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
            throw new InscriptionStartException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new InscriptionStartResponse($response);
    }

    /**
     * @param $token
     * @param null $options
     *
     * @throws InscriptionStatusException
     * @throws GuzzleHttp\Exception\GuzzleException
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
            throw new InscriptionStatusException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new InscriptionStatusResponse($response);
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Options $options
     */
    public function setOptions(Options $options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return $this->getOptions()->getApiBaseUrl();
    }

    /**
     * @param $commerceCode
     * @param $apiKey
     *
     * @return $this
     */
    public static function buildForIntegration($commerceCode, $apiKey)
    {
        return new static(new Options($apiKey, $commerceCode, Options::ENVIRONMENT_INTEGRATION));
    }

    /**
     * @param $commerceCode
     * @param $apiKey
     *
     * @return $this
     */
    public static function buildForProduction($commerceCode, $apiKey)
    {
        return new static(new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION));
    }
}
