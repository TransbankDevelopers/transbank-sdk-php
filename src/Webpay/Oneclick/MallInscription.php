<?php


namespace Transbank\Webpay\Oneclick;

use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\InteractsWithWebpayApi;
use Transbank\Webpay\Oneclick;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionDeleteException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionFinishException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionStartException;
use Transbank\Webpay\Oneclick\Responses\InscriptionDeleteResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionStartResponse;

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
     * @return InscriptionStartResponse
     * @throws InscriptionStartException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function start($username, $email, $responseUrl, $options = null)
    {
        $options = Oneclick::getDefaultOptions($options);
    
        $payload = [
            'username' => $username,
            'email' => $email,
            'response_url' => $responseUrl
        ];
    
        try {
            $response = static::request(
                'POST',
                static::INSCRIPTION_START_ENDPOINT,
                $payload,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw InscriptionStartException::raise($e);
        }
        
        return new InscriptionStartResponse($response);
    }


    public static function finish($token, $options = null)
    {
        $options = Oneclick::getDefaultOptions($options);
    
        try {
            $response = static::request(
                'PUT',
                str_replace('{token}', $token, static::INSCRIPTION_FINISH_ENDPOINT),
                null,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw InscriptionFinishException::raise($e);
        }
        return new InscriptionFinishResponse($response);
    }

    public static function delete($tbkUser, $username, $options = null)
    {
        $options = Oneclick::getDefaultOptions($options);
    
        $payload = [
            'tbk_user' => $tbkUser,
            'username' => $username
        ];
        
        try {
            $response = static::request(
                'DELETE',
                static::INSCRIPTION_DELETE_ENDPOINT,
                $payload,
                $options
            );
        } catch (WebpayRequestException $e) {
            if ($e->getHttpCode() !== 204) {
                return new InscriptionDeleteResponse(false, $e->getHttpCode());
            }
            throw InscriptionDeleteException::raise($e);
        }
        
        return new InscriptionDeleteResponse(true);
    }
}
