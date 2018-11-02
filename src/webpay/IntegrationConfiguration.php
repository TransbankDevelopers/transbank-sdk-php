<?php

namespace Transbank\Webpay;

/**
 * Class IntegrationConfiguration
 *
 * This class is used only on Integration environments. When the Configuration uses
 * testing methods (like forTestingWebpayPlusNormal), the call will fallback
 * to these static methods.
 *
 * @package Transbank\Webpay
 */
class IntegrationConfiguration
{
    /**
     * Creates an Instance with pre-set credentials for Webpay Plus Normal testing
     *
     * @return Configuration
     */
    public static function forTestingWebpayPlusNormal()
    {
        return self::createConfiguration('webpay-plus-normal', 597020000540);
    }

    /**
     * Creates an Instance with pre-set credentials for Webpay Plus Mall testing
     *
     * @return Configuration
     */
    public static function forTestingWebpayPlusMall()
    {
        return self::createConfiguration('webpay-plus-mall', 597044444401);
    }

    /**
     * Creates an Instance with pre-set credentials for Webpay Plus Capture testing
     *
     * @return Configuration
     */
    public static function forTestingWebpayPlusCapture()
    {
        return self::createConfiguration('webpay-plus-capture', 597044444404);
    }

    /**
     * Creates an Instance with pre-set credentials for Webpay Oneclick Normal testing
     *
     * @return Configuration
     */
    public static function forTestingWebpayOneClickNormal()
    {
        return self::createConfiguration('webpay-oneclick-normal', 597044444405);
    }

    /**
     * Creates an Instance with pre-set credentials for Webpay Patpass Normal testing
     *
     * @param string $commerceMail
     * @return Configuration
     */
    public static function forTestingPatPassByWebpayNormal($commerceMail)
    {
        return self::createConfiguration('webpay-patpass-normal', 597020000548, [
            'commerceMail' => $commerceMail,
        ]);
    }

    /**
     * Retrieves a testing Private Key from the filesystem
     *
     * @param $transaction
     * @param $file
     * @return bool|string
     */
    protected static function getKeyFromFile($transaction, $file)
    {
        return file_get_contents(
            __DIR__ . "/../../certificates/integration/$transaction/$file.key"
        );
    }

    /**
     * Retrieves a testing Public Certificate from the filesystem
     *
     * @param $transaction
     * @param $file
     * @return bool|string
     */
    protected static function getCertFromFile($transaction, $file)
    {
        return file_get_contents(
            __DIR__ . "/../../certificates/integration/$transaction/$file.cert"
        );
    }

    /**
     * Creates a new Configuration instance for Integration environment
     *
     * @param string $transaction
     * @param string|int $code
     * @param array $options
     * @return Configuration
     */
    protected static function createConfiguration($transaction, $code, array $options = [])
    {
        return new Configuration(
            array_merge([
                'environment' => 'INTEGRACION',
                'commerceCode' => $code,
                'privateKey' => self::getKeyFromFile($transaction, $code),
                'publicCert' => self::getCertFromFile($transaction, $code),
                'webpayCert' => Webpay::defaultCert()
            ], $options)
        );
    }

}
