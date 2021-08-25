<?php

namespace Transbank\Webpay;

class Webpay
{
    const INTEGRACION = 'INTEGRACION';
    const CERTIFICACION = 'INTEGRACION';
    const TEST = 'INTEGRACION';
    const PRODUCCION = 'PRODUCCION';
    const LIVE = 'PRODUCCION';

    public static function defaultCert($environment = null)
    {
        if (null != $environment) {
            if ($environment == Webpay::PRODUCCION) {
                return "-----BEGIN CERTIFICATE-----\n".
                "MIIDizCCAnOgAwIBAgIJAIXzFTyfjyBkMA0GCSqGSIb3DQEBCwUAMFwxCzAJBgNV\n".
                "BAYTAkNMMQswCQYDVQQIDAJSTTERMA8GA1UEBwwIU2FudGlhZ28xEjAQBgNVBAoM\n".
                "CXRyYW5zYmFuazEMMAoGA1UECwwDUFJEMQswCQYDVQQDDAIxMDAeFw0xODAzMjkx\n".
                "NjA4MjhaFw0yMzAzMjgxNjA4MjhaMFwxCzAJBgNVBAYTAkNMMQswCQYDVQQIDAJS\n".
                "TTERMA8GA1UEBwwIU2FudGlhZ28xEjAQBgNVBAoMCXRyYW5zYmFuazEMMAoGA1UE\n".
                "CwwDUFJEMQswCQYDVQQDDAIxMDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoC\n".
                "ggEBAKRqDk/pv8GeWnEaTVhfw55fThmqbFZOHEc/Un7oVWP+ExjD0kZ/aAwMJZ3d\n".
                "9hpbBExftjoyJ0AYKJXA2CyLGxRp30LapBa2lMehzdP6tC5nrCYbDFz8r8ZyN/ie\n".
                "4lBQ8GjfONq34cLQfM+tOxyazgDYRnZVD9tvOcqI5bFwFKqpn/yMr9Eya7gTo/OP\n".
                "wyz69sAF8MKr0YN941n6C1Cdrzp6cRftdj83nlI75Ue//rMYih/uQYiht4XWFjAA\n".
                "usoOG/IVVCCHhVQGE/Rp22dAF8JzWYZWCe+ICOKjEzEZPjDBqPoh9O+0eGTFVwn2\n".
                "qZf2iSLDKBOiha1wwzpTiiJV368CAwEAAaNQME4wHQYDVR0OBBYEFDfN1Tlj7wbn\n".
                "JIemBNO1XrUOikQpMB8GA1UdIwQYMBaAFDfN1Tlj7wbnJIemBNO1XrUOikQpMAwG\n".
                "A1UdEwQFMAMBAf8wDQYJKoZIhvcNAQELBQADggEBACzXPSHet7aZrQvMUN03jOqq\n".
                "w37brCWZ+L/+pbdOugVRAQRb2W+Z6gyrJ2BuUuiZLCXpjvXACSpwcSB3JesWs9KE\n".
                "YO8E8ofF7a6ORvi2Mw0vpBbwJLqnci1gVlAj3X8r/VbX2rGbvRy+BJAF769xr43X\n".
                "dtns0JIWwKud0xC3iRPMnewo/75HIblbN3guePfouoR2VgfBmeU72UR8O+OpjwbF\n".
                "vpidobGqTGvZtxRV5axer69WY0rAXRhTSfkvyGTXERCJ3vdsF/v9iNKHhERUnpV6\n".
                "KDrfvgD9uqWH12/89hfsfVN6iRH9UOE+SKoR/jHtvLMhVHpa80HVK1qdlfqUTZo=\n".
                '-----END CERTIFICATE-----';
            }
        }

        return "-----BEGIN CERTIFICATE-----\n".
            "MIIC8jCCAdoCCQDZgAhqEGGRFjANBgkqhkiG9w0BAQsFADA7MQswCQYDVQQGEwJD\n".
            "TDERMA8GA1UEBwwIU0FOVElBR08xDDAKBgNVBAoMA1RCSzELMAkGA1UEAwwCMjAw\n".
            "HhcNMjEwODI1MTMyMjE2WhcNMzEwODIzMTMyMjE2WjA7MQswCQYDVQQGEwJDTDER\n".
            "MA8GA1UEBwwIU0FOVElBR08xDDAKBgNVBAoMA1RCSzELMAkGA1UEAwwCMjAwggEi\n".
            "MA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDTJsqnb+tzqySZikBaqGs9t2qV\n".
            "Rg9c8wjPGLPJcHsjk/+K+u8G23CNH+kWyCabI6MXMZ0YdzXP/oiEM1orpCNxuKVN\n".
            "nDkdiae4nbGNo54PbZyBH7bmKgjrZovQZq8sVdjXsXqAAeEQ08Ne3RET3w7s+TDZ\n".
            "x1ZBe+OJ/HONTMbNDGDSDdjCS/VmZlP6xvNDhphC7R8vyBcEo9m/Q3ZuW9lRa+rR\n".
            "5AOvw4RLzwWuJApg03FutQbu2MihnlbxHYuTHsnj0uFT+1Lm2LSqU+WRPnfKH6Gu\n".
            "6j1sb9CiYCczPkSFXYGyNMvSSy6D+0Yd67hmELJ1iPR8vV9vSUflveiMOsfHAgMB\n".
            "AAEwDQYJKoZIhvcNAQELBQADggEBAGTWW5W4+PDSncJgmxS6kJ5WY8Dtx2k+Hzm2\n".
            "J6GsiW8zwuN06Ptw4PbsVlcHcCfBewIMM4YJHuoFh0uMg9C+zPUQQnKHsIUlMCvw\n".
            "sz49WH3fgPpolfMScEgEuo7I9IHxBxILXUA6RScDNjFZpkwpntgT/M0CX0bZt8lA\n".
            "L6SbCGqMu4KhaS+I9oVc9TLMaYZdMnpRBMYx7FyxTWvwfp+r1gKm4SRjt3QMO9gI\n".
            "CmTrfnWrhCeHQen1atuRWm8Q674DzFMdcdEhbexgZsMJXI8TFdpB+FfFT86POJWo\n".
            "a8KTXjkncYkTaOnpMEz+H+xF0fnJ/y9A/A9FgqVhOJIuzPSzBYI=\n".
            "-----END CERTIFICATE-----\n";
    }

    public $configuration;
    public $webpayNormal;
    public $webpayMallNormal;
    public $webpayNullify;
    public $webpayCapture;
    public $webpayOneClick;
    public $webpayCompleteTransaction;

    public function __construct($params)
    {
        $this->configuration = $params;

        if (empty($this->configuration->getWebpayCert())) {
            $this->configuration->setWebpayCert(
                Webpay::defaultCert(
                    $this->configuration->getEnvironment()
                )
            );
        }
    }

    public function getNormalTransaction()
    {
        if ($this->webpayNormal == null) {
            $this->webpayNormal = new WebPayNormal($this->configuration);
        }

        return $this->webpayNormal;
    }

    public function getMallNormalTransaction()
    {
        if ($this->webpayMallNormal == null) {
            $this->webpayMallNormal = new WebPayMallNormal($this->configuration);
        }

        return $this->webpayMallNormal;
    }

    public function getNullifyTransaction()
    {
        if ($this->webpayNullify == null) {
            $this->webpayNullify = new WebpayNullify($this->configuration);
        }

        return $this->webpayNullify;
    }

    public function getCaptureTransaction()
    {
        if ($this->webpayCapture == null) {
            $this->webpayCapture = new WebpayCapture($this->configuration);
        }

        return $this->webpayCapture;
    }

    public function getOneClickTransaction()
    {
        if ($this->webpayOneClick == null) {
            $this->webpayOneClick = new WebpayOneClick($this->configuration);
        }

        return $this->webpayOneClick;
    }

    public function getCompleteTransaction()
    {
        if ($this->webpayCompleteTransaction == null) {
            $this->webpayCompleteTransaction = new WebpayCompleteTransaction($this->configuration);
        }

        return $this->webpayCompleteTransaction;
    }
}
