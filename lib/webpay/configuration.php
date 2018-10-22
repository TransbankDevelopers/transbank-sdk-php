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

    public static function forTestingWebpayPlusMall() {
        $configuration = new Configuration();
        $configuration->setCommerceCode(597044444401);
        $configuration->setPrivateKey(
            "-----BEGIN RSA PRIVATE KEY-----\n" .
            "MIIEowIBAAKCAQEAq5x8P9EIq8xT6UtRAL6pmNpcgYuIXHUvtPuY+Ao28LtbsJQV\n" .
            "gPXJ2CrMYtq3GH1kPAajdF0tdfMOSQgxTnnWsMFdY6jel2vhF1vfKvm79yLMrqIR\n" .
            "X7l/fZbldWJdoSuq1b3xTPYBKKGFhe/SvYpO88dvOuH4WIiAfRT1gFXkEW9xyA70\n" .
            "vK/4RZR93f220ELh8sHBMwP39XnNp7c52A+f1fkJVP5F8G5UTAC/g/jDZMCrtnCw\n" .
            "xu37jyXTEpQATXUN1XrsjJirpvNBfIvXQlk4AeXEj5a7PYE4nASZfNwSc1kpDm7G\n" .
            "6PheDkUXN1JEFscC/x9BpVvZIAjaXML/QCFV7wIDAQABAoIBAF/oGoBHwELS9GpD\n" .
            "D0gNRhcIof48Dr8tNrY8jebBPqcW7k0m1UW3F1DZylPMy9rB6Qyq4RqdIFT0ux0R\n" .
            "mQy0hslNp3WU4KFbRvaY/4Wy/9tD9YP7Sx5mOtvjQuVxTcZO8zB08LAEI+2jJ04N\n" .
            "E4eeDjWrVXxg4TwJPVWqKvHIDqe26CfMlKohSpcCpmq3HQknnFfuxGGlNGdrX4YR\n" .
            "v4BeoSsAG8Ak+cCkGBJ2LcrZpw+GJjs0SkvOVO1+G+vixYPDcor1moB1AnQ/tkrz\n" .
            "gSrRIl+Et3nq5XmmxQejOgMMWaXR2RXutdgXq4w3s4FSwABv5Zw1zAA/yapfk1uH\n" .
            "zJ/OpuECgYEA22kVGXhoR0onMSKKHnbtO3s3tmrgLwVQAMaEwYy8KNnIWkCLszlT\n" .
            "KtJ7nmEDdMysbHpb1EeNAoKg/DKY0YgneWrmmh3JozUp18dXEeHqEVPH1X9XT017\n" .
            "M24nqe65deFu9SKhZv9SQdj69iJLnRxPHSae/p5wb2ORr/XG+9ZX6OkCgYEAyDrK\n" .
            "95yH2b5CcZXvT+9laIO8OZvppTP923a8stofPfBXRmqRZdhLLOVMJhBiQtRSGz4w\n" .
            "Tk0T1LC9FN9Y5y4HLbyYDuYxqda+MqdBoYgsvep4ozVNyE7UDdRTKOjin8xIArAn\n" .
            "mPvhjVBtpvQE+r9A4CfLe2smyHUtW48nAxgugRcCgYAjgfgGLTRDBT8edoZ/s6Nk\n" .
            "0uYLQXSSZ3uxBG+LmykAO25vHK7/DDHnZjTXRr/2cQEedRbTXdj2JQnEhrOwhSZO\n" .
            "QfybyGJPZVUmNH5kyHjG4RYf+QG6NcHQau1EVPvylc8NINOaBYvcWC8VEivGe0Ra\n" .
            "ZVupvR5ZCHYVUeMn8mI7sQKBgQDG5cgq8Z3tSVbdWBAyOl9k07+NBniwt5XLhQZr\n" .
            "L8trDqzTcRbfsVzzyw66nPnO4vRwxXTcwyoY1DvvWPIKKynMYBQ4cKgSyxOCY60J\n" .
            "VakEOr79ePy8Jrn0xt6Yu8Yq8JTzvqKHEGZ8ptFVz/6GSqeaQ02ZWtZauDOHSQt6\n" .
            "wnGnnwKBgFbTBEwXl89uZZ/25z1mA5D9nqHTj/A0GYc9762xc/bvkvi59DeYbNSH\n" .
            "J7jKHS50kE4sS/E4p7e9/G4jZTe/nvEsstfFZprRF31xlVY9Y/1OPysGYIJPOTAg\n" .
            "EBEKSypPpswFcn/jSeIGii7aEb5h6OyNpnSMbBvFxhhUJ1PPpUQG\n" .
            "-----END RSA PRIVATE KEY-----"
        );
        $configuration->setPublicCert(
            "-----BEGIN CERTIFICATE-----\n" .
            "MIIDrDCCApQCCQCDHU0/ZL/yojANBgkqhkiG9w0BAQsFADCBlzELMAkGA1UEBhMC\n" .
            "Y2wxEzARBgNVBAgMClNvbWUtU3RhdGUxETAPBgNVBAcMCHNhbnRpYWdvMSEwHwYD\n" .
            "VQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQxFTATBgNVBAMMDDU5NzA0NDQ0\n" .
            "NDQwMTEmMCQGCSqGSIb3DQEJARYXYW1hbGRvbmFkb0B0cmFuc2JhbmsuY2wwHhcN\n" .
            "MTgwOTA0MTQwMzE4WhcNMjIwMzI3MTQwMzE4WjCBlzELMAkGA1UEBhMCY2wxEzAR\n" .
            "BgNVBAgMClNvbWUtU3RhdGUxETAPBgNVBAcMCHNhbnRpYWdvMSEwHwYDVQQKDBhJ\n" .
            "bnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQxFTATBgNVBAMMDDU5NzA0NDQ0NDQwMTEm\n" .
            "MCQGCSqGSIb3DQEJARYXYW1hbGRvbmFkb0B0cmFuc2JhbmsuY2wwggEiMA0GCSqG\n" .
            "SIb3DQEBAQUAA4IBDwAwggEKAoIBAQCrnHw/0QirzFPpS1EAvqmY2lyBi4hcdS+0\n" .
            "+5j4Cjbwu1uwlBWA9cnYKsxi2rcYfWQ8BqN0XS118w5JCDFOedawwV1jqN6Xa+EX\n" .
            "W98q+bv3IsyuohFfuX99luV1Yl2hK6rVvfFM9gEooYWF79K9ik7zx2864fhYiIB9\n" .
            "FPWAVeQRb3HIDvS8r/hFlH3d/bbQQuHywcEzA/f1ec2ntznYD5/V+QlU/kXwblRM\n" .
            "AL+D+MNkwKu2cLDG7fuPJdMSlABNdQ3VeuyMmKum80F8i9dCWTgB5cSPlrs9gTic\n" .
            "BJl83BJzWSkObsbo+F4ORRc3UkQWxwL/H0GlW9kgCNpcwv9AIVXvAgMBAAEwDQYJ\n" .
            "KoZIhvcNAQELBQADggEBACv0krFTiPCwsw0pwKfHJUqhP+k2B7FkdSFhpdd8OiRX\n" .
            "50E9aY9oiasuojyYA0mdrWDZvyKsxvMGuSzxrxgg42Wsb2DPR5Uc99V2+9rpODFV\n" .
            "nPWeuhAgBUfNK3rZ+qIz1FyrzYUTPcK0BzStbpdclb+LEh7I0wTegSj7skctm8M2\n" .
            "BQmFaS67DUmr0ReI4ZHvWMkDjqjlK8mzx0f7nOdarq3Cxhg3QMqOilfGtvrZrtos\n" .
            "q8/WPGded+bP8kBZ2Rs6oUEBBQfVnAPI50YRXZJjyAzqSwx8MhFztAgE/LaYbvZs\n" .
            "xNB2I18V5oNmOCXHhfqneSstxMBWt3W8rd/0+JSfWLc=\n" .
            "-----END CERTIFICATE-----"
        );
        $configuration->setWebpayCert(Webpay::defaultCert());
        return $configuration;
    }

    public static function forTestingWebpayPlusCapture() {
        $configuration = new Configuration();
        $configuration->setCommerceCode(597044444404);
        $configuration->setPrivateKey(
            "-----BEGIN RSA PRIVATE KEY-----\n" .
            "MIIEpQIBAAKCAQEAq62F11DVV2ciL/S/zKr8NmesZNUoI3t/9EbZ5m97LjH/R1s2\n" .
            "0MxJWjmy0f+I64PvJkGOvQauBdJoGiiCeiV3qY+PppgOQmMo8xXaPzErrVr0F9bx\n" .
            "3gbqSuqi/uwJNJRWUs3tYmlQ/WQrKHSMxpRkVthWoIpyR3UEBpr9N9MhAdDarJCv\n" .
            "/df9Hyu3RpE7ULFCnCHi/y3DVPYi3TnXa4xo9fE3iVuxMNjCUO93GqETYVCRoIW/\n" .
            "237frd8tgZ3biNmYRqbxO6jIv/1uEJs2mxPaW+FmdpW4+yM1tK8e8mWV/OrqpslZ\n" .
            "O0mqUVY16phJJW7ad4fi0V3+TCRwgY0MC6x6uwIDAQABAoIBAQCktQoflXHZNR1b\n" .
            "nRkWp0TqfXSsGMU1pZsRJZiQuIwZueYM87oXgKcvZQPm7Z7TNfUPYv4q5Gm5NDCk\n" .
            "SBFGVwQDLbTIREIJ91CmR2ToE6iv3P8qkBHkzgWicpKGuLXsOBTJxL/nFtuY/61Y\n" .
            "Vtlo+514pH4X8DvLyrxeCfy+vlSAg+mcs+35wnxC+qvAESYXQKODorGljmthkqyd\n" .
            "P/ONKef+PsyJuUevne2YSkiqaDywGBe5JRS+Ij74UH7d+/+hDS9AMwj+nrlVE/Bj\n" .
            "Y1zCw0BijKNuwnoF9oHMZvshfV2GkpS237tmXDbn+fJOqWfonb/CkBtWsF86N79h\n" .
            "/x+gJQ8hAoGBANeQ8/fDA8azfh6z6Ar6C0XIxgZppLtGPac65JhyKCYtvH1kzhJU\n" .
            "dnBqcMOxj9K3zbK3A21Y3gBMZvNS9Wja8Pk7r0R6aHU7eMOlEiOoEdeoVtlOpj4p\n" .
            "Y87Yc8vojd0nnuWJpxw6R8MvzJBm750tIE0/rv2+vfDeaxiWUexhrq0FAoGBAMvh\n" .
            "I5saf9OTJLvBfu8gqldLGdlkG1+1eyxnJbQuHq6o+WsaxjFEwS5a8MOlD7my0tom\n" .
            "zm98fiFHpGX3HGoG9RQuvRKmWRCdj5qb7Bep3gWb9HOKYG+6DKGPPpmJ6fdjV9zh\n" .
            "o5ru5iFJoWHcE60/kVbOJsh6ugVXFzF6DJaBkRS/AoGBAK3Bv4VkgjS2FeD1rwK6\n" .
            "DkAP197vZMM3mRalGAHxcn9jul1w1dJclqOCiKaVB5MYaQu3DWIkkb231/wmUH5W\n" .
            "jIq5G0udR3nHmE5LTlXDca5dmLPM+597iWH/g0dHiqJK/3+R90t/hrzEWKXE8zvE\n" .
            "VhcuUAVkrIHtJnJJKHvbOQtpAoGAXEqPVrAZO0p5r3C5KECOO7PogKs7ZQj/OCt9\n" .
            "OuJBy2j8d0qIe1cXaAeMw9PdmX9kyZIVkww1AJWwyuOg/jImETvTJTUeTlI05pU8\n" .
            "u72OntVpREBYxVrgSuZQPSrcObvD015lNEZ+8ISnRGhek+eZwETT857yxGYXPrN0\n" .
            "LVF7vnsCgYEApWvaUImePJDN50+nExK6TWHGFNnlZPlqFuyEHCSIlC0moGkcdl1D\n" .
            "uILlje5JjmCI6hUreePcpFbyuiWcFcRJPdTgPWAcuWpPqFk3TyMVg8RQjZ4nIb4e\n" .
            "TB3lRTP7u4t4emHHeNZhSeakRMOUYWiylCgSLmmf1OKd/bWTQ6G7lk0=\n" .
            "-----END RSA PRIVATE KEY-----\n"
        );
        $configuration->setPublicCert(
            "-----BEGIN CERTIFICATE-----\n" .
            "MIIDrDCCApQCCQDxS6RHDwxUnjANBgkqhkiG9w0BAQsFADCBlzELMAkGA1UEBhMC\n" .
            "Q0wxEzARBgNVBAgMClNvbWUtU3RhdGUxETAPBgNVBAcMCHNhbnRpYWdvMSEwHwYD\n" .
            "VQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQxFTATBgNVBAMMDDU5NzA0NDQ0\n" .
            "NDQwNDEmMCQGCSqGSIb3DQEJARYXYW1hbGRvbmFkb0B0cmFuc2JhbmsuY2wwHhcN\n" .
            "MTgwOTA0MTQxMDQ3WhcNMjIwMzI3MTQxMDQ3WjCBlzELMAkGA1UEBhMCQ0wxEzAR\n" .
            "BgNVBAgMClNvbWUtU3RhdGUxETAPBgNVBAcMCHNhbnRpYWdvMSEwHwYDVQQKDBhJ\n" .
            "bnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQxFTATBgNVBAMMDDU5NzA0NDQ0NDQwNDEm\n" .
            "MCQGCSqGSIb3DQEJARYXYW1hbGRvbmFkb0B0cmFuc2JhbmsuY2wwggEiMA0GCSqG\n" .
            "SIb3DQEBAQUAA4IBDwAwggEKAoIBAQCrrYXXUNVXZyIv9L/Mqvw2Z6xk1Sgje3/0\n" .
            "Rtnmb3suMf9HWzbQzElaObLR/4jrg+8mQY69Bq4F0mgaKIJ6JXepj4+mmA5CYyjz\n" .
            "Fdo/MSutWvQX1vHeBupK6qL+7Ak0lFZSze1iaVD9ZCsodIzGlGRW2FaginJHdQQG\n" .
            "mv030yEB0NqskK/91/0fK7dGkTtQsUKcIeL/LcNU9iLdOddrjGj18TeJW7Ew2MJQ\n" .
            "73caoRNhUJGghb/bft+t3y2BnduI2ZhGpvE7qMi//W4QmzabE9pb4WZ2lbj7IzW0\n" .
            "rx7yZZX86uqmyVk7SapRVjXqmEklbtp3h+LRXf5MJHCBjQwLrHq7AgMBAAEwDQYJ\n" .
            "KoZIhvcNAQELBQADggEBACo42LiT6Da6Dq6kLrz3ja8dBge2SfCu/gnA+57lENAx\n" .
            "D1Nq3lMqOE2dAoQXM+qwkBvduPaqFUzb4HV1b11PoAgeWR3ksoaKiWwjY5+p/snl\n" .
            "Z/EITwHYhfl9cmuVQFC09AQC/3brrP62fYzKP03CkGrxfVFP0Q0eLzP3w8x4XMzG\n" .
            "9VmMMHFICFYEEyUiQT22X8SpFtUakNCfJzK65zXGAxJqZKTVYhjcYB+HBIAqitGS\n" .
            "hF+F68G9XN7twijNIuseJt/I98R7UazON7EeP7kAz/UylVNOVmYq+pQbU4fG9QjQ\n" .
            "CZ8F118V03v3IQYXwTmOHge9moBwyTkcnI5nql346jg=\n" .
            "-----END CERTIFICATE-----\n"
        );
        $configuration->setWebpayCert(Webpay::defaultCert());
        return $configuration;
    }

}
