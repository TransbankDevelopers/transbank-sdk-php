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
        $decodedResponse = json_decode($httpResponse, true);
        if (!$decodedResponse || !$decodedResponse['responseCode']) {
            throw new RefundCreateException("Could not obtain the service response");
        }
        $refundCreateResponse = new RefundCreateResponse($decodedResponse);
        if (strtolower($decodedResponse['responseCode']) != "ok") {
            $msg = $decodedResponse['responseCode'] . " : " . $decodedResponse['description'];
            throw new RefundCreateException($msg, -1);
        }
        return $refundCreateResponse;
    }
}
