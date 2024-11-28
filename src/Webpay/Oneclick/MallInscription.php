<?php

namespace Transbank\Webpay\Oneclick;

use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionDeleteException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionFinishException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionStartException;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionStartResponse;
use Transbank\Utils\Curl\Exceptions\CurlRequestException;

class MallInscription
{
    use InteractsWithWebpayApi;

    const INSCRIPTION_START_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/inscriptions';
    const INSCRIPTION_FINISH_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/inscriptions/{token}';
    const INSCRIPTION_DELETE_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/inscriptions';

    /**
     * @param string    $username
     * @param string    $email
     * @param string    $responseUrl
     *
     * @return InscriptionStartResponse
     *
     * @throws InscriptionStartException
     * @throws CurlRequestException
     */
    public function start(string $username, string $email, string $responseUrl): InscriptionStartResponse
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
     * @return InscriptionFinishResponse
     *
     * @throws InscriptionFinishException
     * @throws CurlRequestException
     */
    public function finish(string $token): InscriptionFinishResponse
    {
        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::INSCRIPTION_FINISH_ENDPOINT),
                []
            );
        } catch (WebpayRequestException $exception) {
            throw new InscriptionFinishException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new InscriptionFinishResponse($response);
    }

    /**
     * @param string $tbkUser
     * @param string $username
     *
     * @return bool
     *
     * @throws InscriptionDeleteException
     * @throws CurlRequestException
     */
    public function delete(string $tbkUser, string $username): bool
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

            throw new InscriptionDeleteException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return true;
    }
}
