<?php
namespace Transbank\Onepay;
/** 
 * class Refund
 * Model object for Refunds
 * @package Transbank
 * 
 * 
 */
use Transbank\Onepay\Exceptions\RefundCreateException as RefundCreateException;
use Transbank\Utils\HttpClient;

class Refund {
    const REFUND_TRANSACTION = "nullifytransaction";
    const TRANSACTION_BASE_PATH = '/ewallet-plugin-api-services/services/transactionservice/';


    public static function create($amount, $occ, $externalUniqueNumber,
                                  $authorizationCode, $options = null)
    {

        $request = OnepayRequestBuilder::getInstance()
                                       ->buildRefundRequest($amount, $occ, 
                                                            $externalUniqueNumber,
                                                            $authorizationCode,
                                                            $options);
        $jsonRequest = json_encode($request, JSON_UNESCAPED_SLASHES);
        $http = new HttpClient();
        $path = self::TRANSACTION_BASE_PATH . self::REFUND_TRANSACTION;

        $httpResponse = $http->post(OnepayBase::getCurrentIntegrationTypeUrl(),
                                    $path,
                                    $jsonRequest);

        $httpCode = $httpResponse->getStatusCode();
        $responseJson = json_decode($httpResponse->getBody(), true);

        if ($httpCode != 200 && $httpCode != 204) {
            throw new RefundCreateException("Could not obtain the service response");
        }

        $refundCreateResponse = new RefundCreateResponse($responseJson);
        if (strtolower($responseJson['responseCode']) != "ok") {
            $msg = $responseJson['responseCode'] . " : " . $responseJson['description'];
            throw new RefundCreateException($msg, -1);
        }
        return $refundCreateResponse;
    }
}
