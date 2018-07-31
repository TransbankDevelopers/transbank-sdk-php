<?php
namespace Transbank;


/** aqui deberia cargar todo los utils y la api en general */


/**
 * Class OnePay
 *
 * @package Transbank
*/
class OnePay 
{


    public static $appKey = "04533c31-fe7e-43ed-bbc4-1c8ab1538afp";
    public static $callbackUrl = "http://nourlcallbackneededhere";

    public static $serverBasePath;
    public static $scriptPath;
    public static $apiKey;
    public static $sharedSecret;
    private static $integrationType = "MOCK";
    /**
     * Return the API key used for requests
     */

    public static function integrationTypes($type = null) {

        $types = array("TEST" => 'https://web2desa.test.transbank.cl',
                        "LIVE" => '',
                        "MOCK" => 'http://onepay.getsandbox.com');


        if (!$type) {
            return $types;
        }
        else if (!$types[$type]) {
            throw new \Exception('Invalid type, valid types: ' . join(array_keys($types), ", "));
        }
        else {
            return $types[$type];
        }
    }
    
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to use for requests
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }
    /**
     * Return the app key used for requests. Identifies where requests come from
     * on Transbank's side. SDK users have no use for this.
     */
    public static function getAppKey()
    {
        return self::$appKey;
    }
    /**
     * Get the callback URL. Not necessary nor used by integrators, but must be
     * kept for legacy reasons
     */
    public static function getCallbackUrl()
    {
        return self::$callbackUrl;
    }
    /**
     * get the SharedSecret
     */
    public static function getSharedSecret()
    {
        return self::$sharedSecret;
    }
    /**
     * Set the SharedSecret
     */
    public static function setSharedSecret($sharedSecret)
    {
        self::$sharedSecret = $sharedSecret;
    }

    /**
     * Get the url of the current intengration endpoint
     */

    public static function getCurrentIntegrationTypeUrl()
    {
        return self::integrationTypes()[self::$integrationType];
    }
    /**
     * 
     * Get the integration URL of $type
     */

    public static function getIntegrationTypeUrl($type)
    {
        $url = self::integrationTypes()[$type];
        if (!$url) {
            $integrationTypes = array_keys(self::integrationTypes());
            $integrationTypesAsString = join($integrationTypes, ", ");
            throw new \Exception('Invalid integration type, valid values are: ' . $integrationTypesAsString);
        }
        return $url;

    }

    /**
     * Get the current integration type (eg. MOCK, TEST, LIVE)
     */

    public static function getCurrentIntegrationType()
    {
        return self::$integrationType;
    }
    /**
     * Set the integration type
     */

    public static function setCurrentIntegrationType($type)
    {
        if (!self::integrationTypes()[$type]) {
            $integrationTypes = array_keys(self::integrationTypes());
            $integrationTypesAsString = join($integrationTypes, ", ");
            throw new \Exception('Invalid integration type, valid values are: ' . $integrationTypesAsString);
        }
        self::$integrationType = $type;
    }
}
