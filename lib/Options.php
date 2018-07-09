<?php
namespace Transbank;
/**
 * 
 * class Options
 *  Options object used when sending a request to OnePay
 * @package Transbank
 * 
 */

 class Options {

    private $apiKey;
    private $appKey;
    private $sharedSecret;

    public function __construct($apiKey, $appKey, $sharedSecret) {
        $this->setApiKey($apiKey);
        $this->setAppKey($appKey);
        $this->setSharedSecret($sharedSecret);
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        if (!is_string($apiKey)) {
            throw new Exception ('$apiKey must be a string.');
        }
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getAppKey()
    {
        return $this->appKey;
    }

    public function setAppKey($appKey)
    {
        if (!is_string($appKey)) {
            throw new Exception ('$appKey must be a string.');
        }
        $this->appKey = $appKey;
        return $this;
    }

    public function getSharedSecret()
    {
        return $this->sharedSecret;
    }

    public function setSharedSecret($sharedSecret)
    {
        if (!is_string($sharedSecret)) {
            throw new Exception ('$appKey must be a string.');
        }
        $this->sharedSecret = $sharedSecret;
        return $this;
    }

    public static function getDefaults()
    {
        return new Options(OnePay::getApiKey(), OnePay::getAppKey(), OnePay::getSharedSecret());
    }

 }