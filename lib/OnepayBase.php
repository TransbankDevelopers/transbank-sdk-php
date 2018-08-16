<?php
namespace Transbank\Onepay;
/**
 * Class OnepayBase
 * Base class for Transbank\Onepay
 * @package Transbank
*/
class OnepayBase
{
    const DEFAULT_CALLBACK = "http://no.callback.has/been.set";

    public static $appKey = "04533c31-fe7e-43ed-bbc4-1c8ab1538afp";
    public static $callbackUrl = null;

    public static $serverBasePath;
    public static $scriptPath;
    public static $apiKey;
    public static $sharedSecret;
    private static $integrationType = "TEST";
    private static $appScheme = null;

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
    /**
     * Get your Onepay API key
     * If self::$apiKey evaluates to false, it will return getenv("ONEPAY_API_KEY")
     */
    public static function getApiKey()
    {
        if(!self::$apiKey) {
            return getenv("ONEPAY_API_KEY");
        }
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
     * @param mixed $callbackUrl
     * @throws \Exception
     */
    public static function setCallbackUrl($callbackUrl)
    {
        self::$callbackUrl = $callbackUrl;
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
     * Get the SharedSecret
     * If self::$sharedSecret evaluates to false, it will return getenv("ONEPAY_SHARED_SECRET")
     */
    public static function getSharedSecret()
    {
        if(!self::$sharedSecret) {
            return getenv("ONEPAY_SHARED_SECRET");
        }
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

    /**
     * @return mixed
     */
    public static function getAppScheme()
    {
        return self::$appScheme;
    }

    /**
     * @param mixed $appScheme
     */
    public static function setAppScheme($appScheme)
    {
        self::$appScheme = $appScheme;
    }

    public static function DEFAULT_CHANNEL() {
        return ChannelEnum::WEB();
    }

}
