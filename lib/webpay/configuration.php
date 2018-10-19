<?php
namespace Transbank\Webpay;

/**
* @category   Plugins/SDK
* @author     Allware Ltda. (http://www.allware.cl)
* @copyright  2018 Transbank S.A. (http://www.transbank.cl)
* @date       May 2018
* @license    GNU LGPL
* @version    2.0.4
* @link       http://transbankdevelopers.cl/
 */

class Configuration {

    private $environment;
    private $commerce_code;
    private $private_key;
    private $public_cert;
    private $webpay_cert;
    private $store_codes;

    function __construct() {
    }

    public function Configuration() {
    }

    public function getEnvironment() {
        return $this->environment;
    }

    public function setEnvironment($environment) {
        $this->environment = $environment;
    }

    public function getCommerceCode() {
        return $this->commerce_code;
    }

    public function setCommerceCode($commerce_code) {
        $this->commerce_code = $commerce_code;
    }

    public function getPrivateKey() {
        return $this->private_key;
    }

    public function setPrivateKey($private_key) {
        $this->private_key = $private_key;
    }

    public function getPublicCert() {
        return $this->public_cert;
    }

    public function setPublicCert($public_cert) {
        $this->public_cert = $public_cert;
    }

    public function getWebpayCert() {
        return $this->webpay_cert;
    }

    public function setWebpayCert($webpay_cert) {
        $this->webpay_cert = $webpay_cert;
    }

    public function setStoreCodes($store_codes) {
        $this->store_codes = $store_codes;
    }

    public function getStoreCodes() {
        return $this->store_codes;
    }

    public function getEnvironmentDefault() {
        $modo = $this->environment;
        if (!isset($modo) || $modo == "") {
            $modo = "INTEGRACION";
        }
        return $modo;
    }

    public static function forTestingWebpayPlusNormal() {
        $configuration = new Configuration();
        $configuration->setCommerceCode(597020000540);
        $configuration->setPrivateKey(
            "-----BEGIN RSA PRIVATE KEY-----\n" .
            "MIIEowIBAAKCAQEAvuNgBxMAOBlNI7Fw5sHGY1p6DB6EMK83SL4b1ZILSJs/8/MC\n" .
            "X8Pkys3CvJmSIiKU7fnWkgXchEdqXJV+tzgoED/y99tXgoMssi0ma+u9YtPvpT7B\n" .
            "a5rk5HpLuaFNeuE3l+mpkXDZZKFSZJ1fV/Hyn3A1Zz+7+X2qiGrAWWdjeGsIkz4r\n" .
            "uuMFLQVdPVrdAxEWoDRybEUhraQJ1kwmx92HFfRlsbNAmEljG9ngx/+/JLA28cs9\n" .
            "oULy4/M7fVUzioKsBJmjRJd6s4rI2YIDpul6dmgloWgEfzfLNnAsZhJryJNBr2Wb\n" .
            "E6DL5x/U2XQchjishMbDIPjmDgS0HLLMjRCMpQIDAQABAoIBAEkSwa/zliHjjaQc\n" .
            "SRwNEeT2vcHl7LS2XnN6Uy1uuuMQi2rXnBEM7Ii2O9X28/odQuXWvk0n8UKyFAVd\n" .
            "NSTuWmfeEyTO0rEjhfivUAYAOH+coiCf5WtL4FOWfWaSWRaxIJcG2+LRUGc1WlUp\n" .
            "6VXBSR+/1LGxtEPN13phY0DWUz3FEfGBd4CCPLpzq7HyZWEHUvbaw89xZJSr/Zwh\n" .
            "BDZZyTbuwSHc9X9LlQsbaDuW/EyOMmDvSxmSRJO10FRMxyg8qbE4edtUK4jd61i0\n" .
            "kGFqdDu9sj5k8pDxOsN2F270SMlIwejZ1uunB87w9ezIcR9YLq9aa22cT8BZdOxb\n" .
            "uZ3PAAECgYEA6xfgRtcvpJUBWBVNsxrSg6Ktx2848eQne9NnbWHdZuNjH8OyN7SW\n" .
            "Fn0r4HsTw59/NJ1L5F3co5L5baEtRbRLWRpD72xjrXsQSsoKliCik1xgDIplMvOh\n" .
            "teA2GdeSv9wglqnotGcj5B/8+vn3tEzMjy+UUsyFn0fIaDC3zK3W2qUCgYEAz90g\n" .
            "va+FCcU8cnykb5Yn1u1izdK1c6S++v1bQFf6590ZMNy3p0uGrwAk/MzuBkJ421GK\n" .
            "p4pInUvO/Mb2BCcoHtr3ON3v0DCLl6Ae2Gb7lG0dLgcZ1EK7MDpMvKCqNHAv8Qu8\n" .
            "QBZOA08L8buVkkRt7jxJrPuOFDI5JAaWCmMOSgECgYEA3GvzfZgu9Go862B2DJL+\n" .
            "hCuYMiCHTM01c/UfyT/z/Y7/ln2+8FniS02rQPtE6ar28tb0nDahM8EPGon/T5ae\n" .
            "+vkUbzy6LKLxAJ501JPeurnm2Hs+LUqe+U8yioJD9p2m9Hx0UglOborLgGm0pRlI\n" .
            "xou+zu8x7ci5D292NXNcun0CgYAVKV378bKJnBrbTPUwpwjHSMOWUK1IaK1IwCJa\n" .
            "GprgoBHAd7f6wCWmC024ruRMntfO/C4xgFKEMQORmG/TXGkpOwGQOIgBme+cMCDz\n" .
            "xwg1xCYEWZS3l1OXRVgqm/C4BfPbhmZT3/FxRMrigUZo7a6DYn/drH56b+KBWGpO\n" .
            "BGegAQKBgGY7Ikdw288DShbEVi6BFjHKDej3hUfsTwncRhD4IAgALzaatuta7JFW\n" .
            "NrGTVGeK/rE6utA/DPlP0H2EgkUAzt8x3N0MuVoBl/Ow7y5sqIQKfEI7h0aRdXH5\n" .
            "ecefOL6iiJWQqX2+237NOd0fJ4E1+BCMu/+HnyCX+cFM2FgoE6tC\n" .
            "-----END RSA PRIVATE KEY-----\n"
        );
        $configuration->setPublicCert(
            "-----BEGIN CERTIFICATE-----\n" .
            "MIIDeDCCAmACCQDjtGVIe/aeCTANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJj\n" .
            "bDENMAsGA1UECAwEc3RnbzENMAsGA1UEBwwEc3RnbzEMMAoGA1UECgwDdGJrMQ0w\n" .
            "CwYDVQQLDARjY3JyMRUwEwYDVQQDDAw1OTcwMjAwMDA1NDAxHTAbBgkqhkiG9w0B\n" .
            "CQEWDmNjcnJAZ21haWwuY29tMB4XDTE4MDYwODEzNDYwNloXDTIyMDYwNzEzNDYw\n" .
            "NlowfjELMAkGA1UEBhMCY2wxDTALBgNVBAgMBHN0Z28xDTALBgNVBAcMBHN0Z28x\n" .
            "DDAKBgNVBAoMA3RiazENMAsGA1UECwwEY2NycjEVMBMGA1UEAwwMNTk3MDIwMDAw\n" .
            "NTQwMR0wGwYJKoZIhvcNAQkBFg5jY3JyQGdtYWlsLmNvbTCCASIwDQYJKoZIhvcN\n" .
            "AQEBBQADggEPADCCAQoCggEBAL7jYAcTADgZTSOxcObBxmNaegwehDCvN0i+G9WS\n" .
            "C0ibP/PzAl/D5MrNwryZkiIilO351pIF3IRHalyVfrc4KBA/8vfbV4KDLLItJmvr\n" .
            "vWLT76U+wWua5OR6S7mhTXrhN5fpqZFw2WShUmSdX1fx8p9wNWc/u/l9qohqwFln\n" .
            "Y3hrCJM+K7rjBS0FXT1a3QMRFqA0cmxFIa2kCdZMJsfdhxX0ZbGzQJhJYxvZ4Mf/\n" .
            "vySwNvHLPaFC8uPzO31VM4qCrASZo0SXerOKyNmCA6bpenZoJaFoBH83yzZwLGYS\n" .
            "a8iTQa9lmxOgy+cf1Nl0HIY4rITGwyD45g4EtByyzI0QjKUCAwEAATANBgkqhkiG\n" .
            "9w0BAQsFAAOCAQEAhX2/fZ6+lyoY3jSU9QFmbL6ONoDS6wBU7izpjdihnWt7oIME\n" .
            "a51CNssla7ZnMSoBiWUPIegischx6rh8M1q5SjyWYTvnd3v+/rbGa6d40yZW3m+W\n" .
            "p/3Sb1e9FABJhZkAQU2KGMot/b/ncePKHvfSBzQCwbuXWPzrF+B/4ZxGMAkgxtmK\n" .
            "WnWrkcr2qakpHzERn8irKBPhvlifW5sdMH4tz/4SLVwkek24Sp8CVmIIgQR3nyR9\n" .
            "8hi1+Iz4O1FcIQtx17OvhWDXhfEsG0HWygc5KyTqCkVBClVsJPRvoCSTORvukcuW\n" .
            "18gbYO3VlxwXnvzLk4aptC7/8Jq83XY8o0fn+A==\n" .
            "-----END CERTIFICATE-----\n"
        );
        $configuration->setWebpayCert(Webpay::defaultCert());

        return $configuration;
    }

}
