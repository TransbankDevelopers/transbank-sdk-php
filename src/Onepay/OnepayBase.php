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

    public static $appKeys = array("TEST" => '1a0c0639-bd2f-4846-8d26-81f43187e797',
                                   "LIVE" => '2B571C49-C1B6-4AD1-9806-592AC68023B7',
                                   "MOCK" => '04533c31-fe7e-43ed-bbc4-1c8ab1538afp');
    public static $callbackUrl = null;

    public static $serverBasePath;
    public static $scriptPath;
    public static $apiKey;
    public static $sharedSecret;
    private static $integrationType = "TEST";
    private static $appScheme = null;
    /**
     * @var integer|null $qrWidthHeight an integer used as the width & height of the
     *      QR code rendered by the Onepay front end JS SDK.
     */
    private static $qrWidthHeight = null;
    /**
     * @var string|null $commerceLogoUrl URL for the merchant's logo, used by the
     *      Onepay front end JS SDK.
     */
    private static $commerceLogoUrl = null;


    /**
     * @return int|null
     */
    public static function getQrWidthHeight()
    {
        return self::$qrWidthHeight;
    }

    /**
     * @param int|null $qrWidthHeight
     */
    public static function setQrWidthHeight($qrWidthHeight)
    {
        self::$qrWidthHeight = $qrWidthHeight;
    }

    /**
     * @return string|null
     */
    public static function getCommerceLogoUrl()
    {
        return self::$commerceLogoUrl;
    }

    /**
     * @param string|null $commerceLogoUrl
     */
    public static function setCommerceLogoUrl($commerceLogoUrl)
    {
        self::$commerceLogoUrl = $commerceLogoUrl;
    }


    public static function integrationTypes($type = null) {

        $types = array("TEST" => 'https://onepay.ionix.cl',
                       "LIVE" => 'https://www.onepay.cl',
                       "MOCK" => 'https://transbank-onepay-ewallet-mock.herokuapp.com');


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
        if(!self::$callbackUrl) {
            return getenv("ONEPAY_CALLBACK_URL");
        }
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
     * Get the url of the current integration endpoint
     */

    public static function getCurrentIntegrationTypeUrl()
    {
        return self::integrationTypes()[self::$integrationType];
    }

    /**
     * Get the appKey of the current integration endpoint
     */

    public static function getCurrentIntegrationTypeAppKey()
    {
        return self::$appKeys[self::$integrationType];
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
        if(!self::$appScheme) {
            return getenv("ONEPAY_APP_SCHEME");
        }

        return self::$appScheme;
    }

    /**
     * Sets the credentials published by Transbank to play on the TEST
     * environment.
     */
    public static function setIntegrationApiKeyAndSharedSecret() {
        if (null == getenv("ONEPAY_API_KEY"))
            self::setApiKey('dKVhq1WGt_XapIYirTXNyUKoWTDFfxaEV63-O5jcsdw');

        if (null == getenv("ONEPAY_SHARED_SECRET"))
            self::setSharedSecret('?XW#WOLG##FBAGEAYSNQ5APD#JF@$AYZ');
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

OnepayBase::setIntegrationApiKeyAndSharedSecret();
