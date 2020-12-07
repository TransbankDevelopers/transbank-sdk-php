<?php
namespace Transbank\Onepay;
/** 
 * @class BaseRequest
 * Basic request class that includes commonly used members
 * 
 * @package Transbank;
 * 
 * 
 */

 class BaseRequest 
 {
    function __construct() {
        $this->apiKey = null;
        $this->appKey = null;
        $this->generateOttQrCode = true;
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
 }
