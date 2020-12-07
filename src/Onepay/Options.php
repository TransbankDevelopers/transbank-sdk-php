<?php
namespace Transbank\Onepay;
/**
 * 
 * class Options
 *  Options object used when sending a request to Onepay
 * @package Transbank
 * 
 */


 class Options implements \JsonSerializable{

    private $apiKey;
    private $appKey;
    private $sharedSecret;
     /**
      * @var integer $qrWidthHeight A number used as width and height for the
      *     QR displayed by the front end JS SDK.
      */
     private $qrWidthHeight;
     /**
      * @var string $commerceLogoUrl URL for the merchant's logo,
      *     used by the front end JS SDK.
      */
     private $commerceLogoUrl;

     // Supported PHP versions do not allow setting the result of functions
     // as default values as of this writing (Dec 4th, 2018), so we use these
     // constants, since we cannot use null (the user might want to send null as
     // param and not have it take OnepayBase values) nor can we let them have no
     // value (since there are optional params before mandatory params, in which
     // case having that would be confusing or API breaking)
     const __DEFAULT_QR_WIDTH_HEIGHT__ = '__DEFAULT_QR_WIDTH_HEIGHT__';
     const __DEFAULT_COMMERCE_LOGO_URL__ = '__DEFAULT_COMMERCE_LOGO_URL__';

    public function __construct($apiKey = null, $sharedSecret = null,
                                $qrWidthHeight = self::__DEFAULT_QR_WIDTH_HEIGHT__,
                                $commerceLogoUrl = self::__DEFAULT_COMMERCE_LOGO_URL__)
    {
        $this->setApiKey($apiKey);
        $this->setSharedSecret($sharedSecret);
        $this->setQrWidthHeight($qrWidthHeight);
        $this->setCommerceLogoUrl($commerceLogoUrl);

        $this->setAppKey(OnepayBase::getCurrentIntegrationTypeAppKey());
    }

     public static function getDefaults()
     {
         return new Options(OnepayBase::getApiKey(),
             OnepayBase::getSharedSecret(),
             OnepayBase::getQrWidthHeight(),
             OnepayBase::getCommerceLogoUrl());
     }

     public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getAppKey()
    {
        return $this->appKey;
    }

    public function setAppKey($appKey)
    {
        $this->appKey = $appKey;
        return $this;
    }

    public function getSharedSecret()
    {
        return $this->sharedSecret;
    }

    public function setSharedSecret($sharedSecret)
    {
        $this->sharedSecret = $sharedSecret;
        return $this;
    }

     /**
      * @return integer|null
      */
     public function getQrWidthHeight()
     {
         return $this->qrWidthHeight;
     }

     /**
      * @param integer|null $qrWidthHeight
      * @return $this
      */
     public function setQrWidthHeight($qrWidthHeight)
     {
         if ($qrWidthHeight == self::__DEFAULT_QR_WIDTH_HEIGHT__) {
             $qrWidthHeight = OnepayBase::getQrWidthHeight();
         }
         $this->qrWidthHeight = $qrWidthHeight;
         return $this;
     }

     /**
      * @return string|null
      */
     public function getCommerceLogoUrl()
     {
         return $this->commerceLogoUrl;
     }

     /**
      * @param string|null $commerceLogoUrl
      * @return $this
      */
     public function setCommerceLogoUrl($commerceLogoUrl)
     {
         if ($commerceLogoUrl == self::__DEFAULT_COMMERCE_LOGO_URL__) {
             $commerceLogoUrl = OnepayBase::getCommerceLogoUrl();
         }
         $this->commerceLogoUrl = $commerceLogoUrl;
         return $this;
     }
 }
