<?php


namespace Transbank\Webpay\SoapClassMaps;


class CaptureOutput
{
    public $authorizationCode; //string
    public $authorizationDate; //dateTime
    public $capturedAmount; //decimal
    public $token; //string
}
