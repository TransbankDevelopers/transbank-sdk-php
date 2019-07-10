<?php


namespace Transbank\Webpay\Oneclick;


use Transbank\Webpay\Exceptions\AuthorizeMallTransactionException;
use Transbank\Webpay\Exceptions\InscriptionStartException;
use Transbank\Webpay\Oneclick;

class MallTransaction
{

    const AUTHORIZE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.0/transaction';
    const TRANSACTION_STATUS_ENDPONT = 'rswebpaytransaction/api/oneclick/v1.0/transactions/$BUYORDER$';
    const TRANSACTION_REFUND_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.0/transactions/$BUYORDER$/refund';

    public static function authorize($userName, $tbkUser, $parentBuyOrder, $details,
                                     $options = null)
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

        $payload = json_encode([
            "username" => $userName,
            "tbk_user" => $tbkUser,
            "buy_order" => $parentBuyOrder,
            "details" => $details]);

        $httpResponse = $http->post($baseUrl,
            self::AUTHORIZE_TRANSACTION_ENDPOINT,
            $payload,
            ['headers' => $headers]
        );

        if (!$httpResponse) {
            throw new AuthorizeMallTransactionException('Could not obtain a response from the service', -1);
        }

        $responseJson = json_decode($httpResponse, true);
        if (isset($responseJson['error_message'])) {
            throw new AuthorizeMallTransactionException($responseJson['error_message']);
        }
        $json = json_decode($httpResponse, true);

        $authorizeTransactionResponse = new AuthorizeMallTransactionResponse($json);

        return $authorizeTransactionResponse;
    }

    public static function getStatus($buyOrder, $options = null)
    {

    }

    public static function refund($buyOrder, $amount, $options = null)
    {

    }


}
