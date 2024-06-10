<?php

namespace Transbank\Webpay\Oneclick;

use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Oneclick;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionDeleteException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionFinishException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionStartException;
use Transbank\Webpay\Oneclick\Responses\InscriptionDeleteResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionStartResponse;
use Transbank\Webpay\Options;

class MallInscription
{
    use InteractsWithWebpayApi;

    const INSCRIPTION_START_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/inscriptions';
    const INSCRIPTION_FINISH_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/inscriptions/{token}';
    const INSCRIPTION_DELETE_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/inscriptions';

    /**
     * @param $username
     * @param $email
     * @param $responseUrl
     * @param null $options
     *
     * @throws InscriptionStartException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return InscriptionStartResponse
     */
    public function start($username, $email, $responseUrl)
    {
        $payload = [
            'username'     => $username,
            'email'        => $email,
            'response_url' => $responseUrl,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                static::INSCRIPTION_START_ENDPOINT,
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw new InscriptionStartException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new InscriptionStartResponse($response);
    }

    public function finish($token)
    {
        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::INSCRIPTION_FINISH_ENDPOINT),
                null
            );
        } catch (WebpayRequestException $exception) {
            throw new InscriptionFinishException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new InscriptionFinishResponse($response);
    }

    public function delete($tbkUser, $username)
    {
        $payload = [
            'tbk_user' => $tbkUser,
            'username' => $username,
        ];

        try {
            $this->sendRequest(
                'DELETE',
                static::INSCRIPTION_DELETE_ENDPOINT,
                $payload
            );
        } catch (WebpayRequestException $exception) {
            if ($exception->getHttpCode() !== 204) {
                return new InscriptionDeleteResponse(false, $exception->getHttpCode());
            }

            throw new InscriptionDeleteException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new InscriptionDeleteResponse(true);
    }
}
