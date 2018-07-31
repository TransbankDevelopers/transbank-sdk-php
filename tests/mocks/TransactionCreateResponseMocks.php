<?php

namespace Transbank\Onepay;

class TransactionCreateResponseMocks {
    public static $transactionCreateResponseMocks = array();

    public static function transactionCreateRequestMocks() {
        if(empty(self::$transactionCreateResponseMocks)) {
            $validResponseJson = '{
                "responseCode": "OK",
                "description": "OK",
                "result": {
                    "occ": "1807216892091979",
                    "ott": 51435450,
                    "signature": "i1xFsNiky1VrEoXWUWXqGh9R4yg1/rfZhczEChhwHEU=",
                    "externalUniqueNumber": "1532103675510",
                    "issuedAt": 1532103850,
                    "qrCodeAsBase64": "iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAADqElEQVR42u3dQW7DMBAEQf3/08kLcgggame41UBugSGLLB8Wlvn8SPqzxy2QAJEAkQCRAJEAkQCRAJEAkQCRBIgEiASIBIgEiASIBIgEiASIBIgkQCRAJEAkQKQtQJ7nqfj77/W3/P+29QIEEEAAAQQQQAABBBBAAAEEEEAAefeGj43uXrqeGzbApvUCBBDrBQgg1gsQQAABBBBAAAEEEEDefYMtY9vTG34KVPt6AQIIIIAAAggggAACCCCAAAIIIIAA8uX1pL0OIIAAAggggAACCCCAAAIIIIAAAgggjUDSxrZTrwMIIIAAAggggAACCCCAAAIIIIAAshNI+/W0bwyP3AICiPUCBBDrBQgg1gsQQAABBBBAAHH8Qe//O/4AEEAAAcSGBwQQQAABBBBAAAEEkLuBbGvboZ9Xr6VbAIgAAQQQQAABBBBAAAEEEEAWAUkb97WPSacgn36/icABAQQQQAABBBBAAAEEEEAAAQSQTUCmNtKtxwe0jKONeQEBBBBAAAEEEEAAAQQQQAABBJA7xrxp48d24FMbO/FRWUAAAQQQQAABBBBAAAEEEEAAAQSQOSAtX2JMO7ag/XqcDwIIIIAAAggggAACCCCAAAIIIIBkPnKbNlZtOV4h7T7fMBYGBBBAAAEEEEAAAQQQQAABBBBANgFpH1e2f1Ccvs6WL5cCAggggAACCCCAAAIIIIAAAggggLy7YdIWtGX8e3qMPDWmXjvmBQQQQAABBBBAAAEEEEAAAQQQQD4G0n4cQMsPwbWPYQEBBBBAAAEEEEAAAQQQQAABBBBAMse8UzeqZew59YHT8ogxIIAAAggggAACCCCAAAIIIIAAAoiSF3RqzNvygQAIIIAAAggggAACCCCAAAIIIIAAMrugaV8aPL2gLWNVPxwHCCCAAAIIIIAAAggggAACCCCA3A2kZWybNg5tHzs37R9AAAEEEEAAAQQQQAABBBBAAAEEkPOPiKZtmLQxb/s4HRBAAAEEEEAAAQQQQAABBBBAAAEEkC+vJ25TlP8wHSCAAAIIIIAAAggggAACCCCAAAIIIAkL136Y6dT7AgQQQAABBBBAAAEEEEAAAQQQQACZXdBbx5i3bsimMTgggAACCCCAAAIIIIAAAggggAACSP9GuvUwzZb7CQgggAACCCCAAAIIIIAAAggggAAiCRAJEAkQCRAJEAkQCRAJEAkQCRBJgEiASIBIgEiASIBIgEiASIBIAkQCRAJEAkQCRErpF7hX1b0GLrAmAAAAAElFTkSuQmCC"
                }
            }';

            $transactionCreateResponse = new TransactionCreateResponse($validResponseJson);
            array_push(self::$transactionCreateResponseMocks, $transactionCreateResponse);
        }
        return self::$transactionCreateResponseMocks;
    }

    public static function get($indexOfMock = 0)
    {
        return self::transactionCreateRequestMocks()[$indexOfMock];
    }
}
