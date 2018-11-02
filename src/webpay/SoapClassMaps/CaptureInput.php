<?php


namespace Transbank\Webpay\SoapClassMaps;


class CaptureInput
{
    public $commerceId; //long
    public $buyOrder; //string
    public $authorizationCode; //string
    public $captureAmount; //decimal
}
