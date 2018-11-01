<?php
namespace Transbank\Webpay;

class Webpay {

    const INTEGRACION = "INTEGRACION";
    const CERTIFICACION = "INTEGRACION";
    const TEST = "INTEGRACION";
    const PRODUCCION = "PRODUCCION";
    const LIVE = "PRODUCCION";

    public static function defaultCert($environment = null) {
        if (null != $environment) {
            if ($environment == Webpay::PRODUCCION) {
                return "-----BEGIN CERTIFICATE-----\n" .
                    "MIIG2TCCBcGgAwIBAgIQB2pxSISZbltfSuf1zZEL0TANBgkqhkiG9w0BAQsFADBw\n" .
                    "MQswCQYDVQQGEwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3\n" .
                    "d3cuZGlnaWNlcnQuY29tMS8wLQYDVQQDEyZEaWdpQ2VydCBTSEEyIEhpZ2ggQXNz\n" .
                    "dXJhbmNlIFNlcnZlciBDQTAeFw0xODA4MDMwMDAwMDBaFw0yMDEwMDgxMjAwMDBa\n" .
                    "MGYxCzAJBgNVBAYTAkNMMREwDwYDVQQHEwhTYW50aWFnbzEXMBUGA1UEChMOVFJB\n" .
                    "TlNCQU5LIFMuQS4xEjAQBgNVBAsTCVNlZ3VyaWRhZDEXMBUGA1UEAwwOKi50cmFu\n" .
                    "c2JhbmsuY2wwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCvMmn+SSoP\n" .
                    "Y3kg4xefyawsRsD3AdS7J0o6NQFTakVepLsLeP0Q8jXyoHO0hOI73HP6ExQSkyqH\n" .
                    "wXVviQjxCiwRJSTVUwSZ83iOIzfY/+P5FjQTD88EgWxzAAle8bFtRvq/RPMfOTPg\n" .
                    "F8LQpOBF0JWojbBnJnnMr936KYe2pz3i15100lQb/Q1g5vGtksJfWUHqdCPRSAzB\n" .
                    "tIQm5zOFCZQXBzQQFuGgh1qA6JuflAJObYeTbP0m6xLZPrB0BtCfin4Y5ElRx33j\n" .
                    "nxokFvrlyn0mHNkrKFfYvYgLt4LXo+Fr5Jb74SpL7MYUGOxrdamtMybdY6NblIrQ\n" .
                    "YrTyjcN9ytAlAgMBAAGjggN3MIIDczAfBgNVHSMEGDAWgBRRaP+QrwIHdTzM2WVk\n" .
                    "YqISuFlyOzAdBgNVHQ4EFgQUzC/DndMX66wDwTyUUSos058Y8wwwJwYDVR0RBCAw\n" .
                    "HoIOKi50cmFuc2JhbmsuY2yCDHRyYW5zYmFuay5jbDAOBgNVHQ8BAf8EBAMCBaAw\n" .
                    "HQYDVR0lBBYwFAYIKwYBBQUHAwEGCCsGAQUFBwMCMHUGA1UdHwRuMGwwNKAyoDCG\n" .
                    "Lmh0dHA6Ly9jcmwzLmRpZ2ljZXJ0LmNvbS9zaGEyLWhhLXNlcnZlci1nNi5jcmww\n" .
                    "NKAyoDCGLmh0dHA6Ly9jcmw0LmRpZ2ljZXJ0LmNvbS9zaGEyLWhhLXNlcnZlci1n\n" .
                    "Ni5jcmwwTAYDVR0gBEUwQzA3BglghkgBhv1sAQEwKjAoBggrBgEFBQcCARYcaHR0\n" .
                    "cHM6Ly93d3cuZGlnaWNlcnQuY29tL0NQUzAIBgZngQwBAgIwgYMGCCsGAQUFBwEB\n" .
                    "BHcwdTAkBggrBgEFBQcwAYYYaHR0cDovL29jc3AuZGlnaWNlcnQuY29tME0GCCsG\n" .
                    "AQUFBzAChkFodHRwOi8vY2FjZXJ0cy5kaWdpY2VydC5jb20vRGlnaUNlcnRTSEEy\n" .
                    "SGlnaEFzc3VyYW5jZVNlcnZlckNBLmNydDAMBgNVHRMBAf8EAjAAMIIBfgYKKwYB\n" .
                    "BAHWeQIEAgSCAW4EggFqAWgAdQC72d+8H4pxtZOUI5eqkntHOFeVCqtS6BqQlmQ2\n" .
                    "jh7RhQAAAWUBzO3HAAAEAwBGMEQCIEMQFiHzSzPGKyr7eHSVoVY80hsFeIvVf3Fe\n" .
                    "a8qv+0tLAiBncjRBFOS0qBgvMnk8MgJ4hJyXX1YOyxJRspP4Y58ADwB3AId1v+dZ\n" .
                    "fPiMQ5lfvfNu/1aNR1Y2/0q1YMG06v9eoIMPAAABZQHM7ooAAAQDAEgwRgIhAILr\n" .
                    "1WEB5yatR7P3HCf7MrJ63L3fH90QbSNfxVA1ovjOAiEA85Xta6mlacAY22AbKlt3\n" .
                    "W19sE3FjEuBrVxUgjJ0+TrIAdgBvU3asMfAxGdiZAKRRFf93FRwR2QLBACkGjbII\n" .
                    "mjfZEwAAAWUBzO+DAAAEAwBHMEUCIFY9taJ7ZRCXlw0r7/q2jCppMLxcj21D1r0j\n" .
                    "hgEQDXn0AiEA0XPlBStsg+J7GYL3DQFG55UVm3AIVOrN6UapP+9hERowDQYJKoZI\n" .
                    "hvcNAQELBQADggEBAA5HqaP0855FpuBYo5FN/xe/AZ0ofzzh1QvdI5IxDx/CLMN0\n" .
                    "mpvrXVi+mxefb71NioDI5xYRXpg5gVOTNx3c65pfnrNXxx7F5bcYoH1KyEaVr0lV\n" .
                    "6PFfCK3p4lpYPDMon20K83lzSUc/sVuvidAQgAtHPYTFTc1xHPVer/XFq3m8RXyu\n" .
                    "44GKMvCCSPGrLaclWU5UI4jwrNkR2iiE2IQCNxzxImxthU6jQEabCUtL3K5s9kvq\n" .
                    "1PtoneUkS0gdKhAWe9CyoVRqP71yX1R2RbGqSUDNH3QJgibJmqHOTNBrD01mKYxK\n" .
                    "3jRv7Rl4ETKXtdXyHd4PDyXCfRZZjqUS7kHSWEQ=\n" .
                    "-----END CERTIFICATE-----\n";
            }
        }

        return "-----BEGIN CERTIFICATE-----\n" .
            "MIIEDzCCAvegAwIBAgIJAMaH4DFTKdnJMA0GCSqGSIb3DQEBCwUAMIGdMQswCQYD\n" .
            "VQQGEwJDTDERMA8GA1UECAwIU2FudGlhZ28xETAPBgNVBAcMCFNhbnRpYWdvMRcw\n" .
            "FQYDVQQKDA5UUkFOU0JBTksgUy5BLjESMBAGA1UECwwJU2VndXJpZGFkMQswCQYD\n" .
            "VQQDDAIyMDEuMCwGCSqGSIb3DQEJARYfc2VndXJpZGFkb3BlcmF0aXZhQHRyYW5z\n" .
            "YmFuay5jbDAeFw0xODA4MjQxOTU2MDlaFw0yMTA4MjMxOTU2MDlaMIGdMQswCQYD\n" .
            "VQQGEwJDTDERMA8GA1UECAwIU2FudGlhZ28xETAPBgNVBAcMCFNhbnRpYWdvMRcw\n" .
            "FQYDVQQKDA5UUkFOU0JBTksgUy5BLjESMBAGA1UECwwJU2VndXJpZGFkMQswCQYD\n" .
            "VQQDDAIyMDEuMCwGCSqGSIb3DQEJARYfc2VndXJpZGFkb3BlcmF0aXZhQHRyYW5z\n" .
            "YmFuay5jbDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAJN+OJgQQqMb\n" .
            "iRZDb3x+JoTfSjyYsRc5k2CWvLpTPFxXuhDyp6mbdIpWIiNYEC4vufVZo5A3THar\n" .
            "cbnJRlW/4NVv5QM3gHN9WJ4QeIsrTLtvcIPlfUJNPLNeDqy84zum2YqAFmX5LWsp\n" .
            "SF1Ls6n7el8KNJAceaU+2ooN8QZdFZ3RnMc2vrHY7EU6wYGmf/VCEaDZCKqY6ElY\n" .
            "mt6/9b2lkhpQLdBn01IqqFpGrD+5DLmYrQur4/1BDVtdNLggX0K7kPk/mkPDq4ME\n" .
            "ytkc9/RI5HfJWoQ4EDQF6qcqPqxlMFDf5KEaoLVL230EdwOl0UyvlF25S9ubRyHy\n" .
            "mKWIEFSSXe0CAwEAAaNQME4wHQYDVR0OBBYEFP3nYSPX3YKF11RArC09hxjEMMBv\n" .
            "MB8GA1UdIwQYMBaAFP3nYSPX3YKF11RArC09hxjEMMBvMAwGA1UdEwQFMAMBAf8w\n" .
            "DQYJKoZIhvcNAQELBQADggEBAFHqOPGeg5IpeKz9LviiBGsJDReGVkQECXHp1QP4\n" .
            "8RpWDdXBKQqKUi7As97wmVksweaasnGlgL4YHShtJVPFbYG9COB+ElAaaiOoELsy\n" .
            "kjF3tyb0EgZ0Z3QIKabwxsxdBXmVyHjd13w6XGheca9QFane4GaqVhPVJJIH/zD2\n" .
            "mSc1boVSpaRc1f0oiMtiZf/rcY1/IyMXA9RVxtOtNs87Wjnwq6AiMjB15fLHfT7d\n" .
            "R48O6P0ZpWLlZwScyqDWcsg/4wNCL5Kaa5VgM03SKM6XoWTzkT7p0t0FPZVoGCyG\n" .
            "MX5lzVXafBH/sPd545fBH2J3xAY3jtP764G4M8JayOFzGB0=\n" .
            "-----END CERTIFICATE-----\n";
    }

    var $configuration, $webpayNormal, $webpayMallNormal, $webpayNullify, $webpayCapture, $webpayOneClick, $webpayCompleteTransaction;

    function __construct($params) {

        $this->configuration = $params;
    }

    public function getNormalTransaction() {
        if ($this->webpayNormal == null) {
            $this->webpayNormal = new WebPayNormal($this->configuration);
        }
        return $this->webpayNormal;
    }

    public function getMallNormalTransaction() {
        if ($this->webpayMallNormal == null) {
            $this->webpayMallNormal = new WebPayMallNormal($this->configuration);
        }
        return $this->webpayMallNormal;
    }

    public function getNullifyTransaction() {
        if ($this->webpayNullify == null) {
            $this->webpayNullify = new WebpayNullify($this->configuration);
        }
        return $this->webpayNullify;
    }

    public function getCaptureTransaction() {
        if ($this->webpayCapture == null) {
            $this->webpayCapture = new WebpayCapture($this->configuration);
        }
        return $this->webpayCapture;
    }

    public function getOneClickTransaction() {
        if ($this->webpayOneClick == null) {
            $this->webpayOneClick = new WebpayOneClick($this->configuration);
        }
        return $this->webpayOneClick;
    }

    public function getCompleteTransaction() {
        if ($this->webpayCompleteTransaction == null) {
            $this->webpayCompleteTransaction = new WebpayCompleteTransaction($this->configuration);
        }
        return $this->webpayCompleteTransaction;
    }

}
