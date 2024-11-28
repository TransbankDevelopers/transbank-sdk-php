<?php

/**
 * Class Inscription.
 *
 * @category
 */

namespace Transbank\PatpassComercio;

use Transbank\PatpassComercio\Exceptions\InscriptionStartException;
use Transbank\PatpassComercio\Exceptions\InscriptionStatusException;
use Transbank\PatpassComercio\Responses\InscriptionStartResponse;
use Transbank\PatpassComercio\Responses\InscriptionStatusResponse;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Utils\RequestServiceTrait;
use Transbank\Contracts\RequestService;
use Transbank\PatpassComercio\Options;
use GuzzleHttp\Exception\GuzzleException;

class Inscription
{
    use RequestServiceTrait;
    const INSCRIPTION_START_ENDPOINT = 'restpatpass/v1/services/patInscription';
    const INSCRIPTION_STATUS_ENDPOINT = 'restpatpass/v1/services/status';

    /**
     * @var Options
     */
    protected Options $options;

    /**
     * Transaction constructor.
     *
     * @param Options          $options
     * @param RequestService|null  $requestService
     */
    public function __construct(
        Options $options,
        RequestService|null $requestService = null
    ) {
        $this->options = $options;
        $this->setRequestService($requestService !== null ? $requestService :
            new HttpClientRequestService());
    }

    /**
     * @param string $url
     * @param string $name
     * @param string $lastName
     * @param string $secondLastName
     * @param string $rut
     * @param string $serviceId
     * @param string $finalUrl
     * @param string $maxAmount
     * @param string $phone
     * @param string $cellPhone
     * @param string $patpassName
     * @param string $personEmail
     * @param string $commerceEmail
     * @param string $address
     * @param string $city
     *
     * @throws InscriptionStartException
     * @throws GuzzleException
     *
     * @return InscriptionStartResponse
     */
    public function start(
        string $url,
        string $name,
        string $lastName,
        string $secondLastName,
        string $rut,
        string $serviceId,
        string $finalUrl,
        string $maxAmount,
        string $phone,
        string $cellPhone,
        string $patpassName,
        string $personEmail,
        string $commerceEmail,
        string $address,
        string $city
    ): InscriptionStartResponse {
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
     * @param string $token
     *
     * @throws InscriptionStatusException
     * @throws GuzzleException
     *
     * @return InscriptionStatusResponse
     */
    public function status(string $token): InscriptionStatusResponse
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
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * @param Options $options
     *
     * @return void
     */
    public function setOptions(Options $options): void
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return $this->getOptions()->getApiBaseUrl();
    }

    /**
     * @param string $apiKey
     * @param string $commerceCode
     *
     * @return self
     */
    public static function buildForIntegration(string $apiKey, string $commerceCode): self
    {
        return new self(new Options($apiKey, $commerceCode, Options::ENVIRONMENT_INTEGRATION));
    }

    /**
     * @param string $apiKey
     * @param string $commerceCode
     *
     * @return self
     */
    public static function buildForProduction(string $apiKey, string $commerceCode): self
    {
        return new self(new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION));
    }
}
