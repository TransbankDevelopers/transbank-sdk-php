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

    public function __construct($apiKey = null, $sharedSecret = null)
    {
        $this->setApiKey($apiKey);
        $this->setSharedSecret($sharedSecret);
        $this->setAppKey(OnepayBase::getCurrentIntegrationTypeAppKey());
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

    public static function getDefaults()
    {
        return new Options(OnepayBase::getApiKey(), OnepayBase::getSharedSecret());
    }
 }
