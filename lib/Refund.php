<?php
namespace Transbank;
/** 
 * class Refund
 * Model object for Refunds
 * @package Transbank
 * 
 * 
 */
class Refund {
    const REFUND_TRANSACTION = "nullifytransaction";
    const TRANSACTION_BASE_PATH = '/ewallet-plugin-api-services/services/transactionservice/';


    public static function create($amount, $occ, $externalUniqueNumber,
                                  $authorizationCode, $options = null)
    {
        if(!$options) {
            $options = self::buildOptions($options);
        }

        $request = OnePayRequestBuilder.getInstance()
                                       ->buildRefundRequest($amount, $occ, 
                                                            $externalUniqueNumber,
                                                            $authorizationCode,
                                                            $options);
        $jsonRequest = json_encode($request, JSON_UNESCAPED_SLASHES);
        $http = new HttpClient();
        $path = self::TRANSACTION_BASE_PATH . self::REFUND_TRANSACTION;
        $httpResponse = $http->post(OnePay::getCurrentIntegrationTypeUrl(),
                                    $path,
                                    $jsonRequest);
        return (new RefundCreateResponse())->fromJSON($httpResponse);
    }


}
