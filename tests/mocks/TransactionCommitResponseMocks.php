<?php

namespace Transbank\Onepay;

class TransactionCommitResponseMocks {
    public static $transactionCommitResponseMocks = array();

    public static function transactionCommitRequestMocks() {
        
        if(empty(self::$transactionCommitResponseMocks)) {
            $validResponseJson = '{
                "responseCode": "OK",
                "description": "OK",
                "result": {
                    "occ": "1807419329781765",
                    "authorizationCode": "906637",
                    "issuedAt": 1530822491,
                    "signature": "oM1mqjNfH/mv2TxR5Qf4VN0hr6eNCLsjfjJShdr9Vg0=",
                    "amount": 2490,
                    "transactionDesc": "Venta Normal: Sin cuotas",
                    "installmentsAmount": 2490,
                    "installmentsNumber": 1,
                    "buyOrder": "20180705161636514"
                }
            }';
            $transactionCommitResponse = new TransactionCommitResponse($validResponseJson);
            
            array_push(self::$transactionCommitResponseMocks, $transactionCommitResponse);
        }
        return self::$transactionCommitResponseMocks;
    }

    public static function get($indexOfMock = 0)
    {
        return self::transactionCommitRequestMocks()[$indexOfMock];
    }
}
