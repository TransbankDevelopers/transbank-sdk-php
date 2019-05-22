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
                "MIIDizCCAnOgAwIBAgIJAIXzFTyfjyBkMA0GCSqGSIb3DQEBCwUAMFwxCzAJBgNV\n" .
                "BAYTAkNMMQswCQYDVQQIDAJSTTERMA8GA1UEBwwIU2FudGlhZ28xEjAQBgNVBAoM\n" .
                "CXRyYW5zYmFuazEMMAoGA1UECwwDUFJEMQswCQYDVQQDDAIxMDAeFw0xODAzMjkx\n" .
                "NjA4MjhaFw0yMzAzMjgxNjA4MjhaMFwxCzAJBgNVBAYTAkNMMQswCQYDVQQIDAJS\n" .
                "TTERMA8GA1UEBwwIU2FudGlhZ28xEjAQBgNVBAoMCXRyYW5zYmFuazEMMAoGA1UE\n" .
                "CwwDUFJEMQswCQYDVQQDDAIxMDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoC\n" .
                "ggEBAKRqDk/pv8GeWnEaTVhfw55fThmqbFZOHEc/Un7oVWP+ExjD0kZ/aAwMJZ3d\n" .
                "9hpbBExftjoyJ0AYKJXA2CyLGxRp30LapBa2lMehzdP6tC5nrCYbDFz8r8ZyN/ie\n" .
                "4lBQ8GjfONq34cLQfM+tOxyazgDYRnZVD9tvOcqI5bFwFKqpn/yMr9Eya7gTo/OP\n" .
                "wyz69sAF8MKr0YN941n6C1Cdrzp6cRftdj83nlI75Ue//rMYih/uQYiht4XWFjAA\n" .
                "usoOG/IVVCCHhVQGE/Rp22dAF8JzWYZWCe+ICOKjEzEZPjDBqPoh9O+0eGTFVwn2\n" .
                "qZf2iSLDKBOiha1wwzpTiiJV368CAwEAAaNQME4wHQYDVR0OBBYEFDfN1Tlj7wbn\n" .
                "JIemBNO1XrUOikQpMB8GA1UdIwQYMBaAFDfN1Tlj7wbnJIemBNO1XrUOikQpMAwG\n" .
                "A1UdEwQFMAMBAf8wDQYJKoZIhvcNAQELBQADggEBACzXPSHet7aZrQvMUN03jOqq\n" .
                "w37brCWZ+L/+pbdOugVRAQRb2W+Z6gyrJ2BuUuiZLCXpjvXACSpwcSB3JesWs9KE\n" .
                "YO8E8ofF7a6ORvi2Mw0vpBbwJLqnci1gVlAj3X8r/VbX2rGbvRy+BJAF769xr43X\n" .
                "dtns0JIWwKud0xC3iRPMnewo/75HIblbN3guePfouoR2VgfBmeU72UR8O+OpjwbF\n" .
                "vpidobGqTGvZtxRV5axer69WY0rAXRhTSfkvyGTXERCJ3vdsF/v9iNKHhERUnpV6\n" .
                "KDrfvgD9uqWH12/89hfsfVN6iRH9UOE+SKoR/jHtvLMhVHpa80HVK1qdlfqUTZo=\n" .
                "-----END CERTIFICATE-----";
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

        if (empty($this->configuration->getWebpayCert()))
            $this->configuration->setWebpayCert(Webpay::defaultCert(
                $this->configuration->getEnvironment())
            );
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
