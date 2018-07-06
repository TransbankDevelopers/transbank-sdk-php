<?php
namespace Transbank;
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
        $this->$apiKey = null;
        $this->$appKey = null;
        $this->$generateOttQrCode = true;
    }

    public function setApiKey($apiKey) 
    {
        $this->$apiKey = $apiKey;
    }

    public function setAppKey($appKey) 
    {
        $this->$appKey = $appKey;
    }
 }