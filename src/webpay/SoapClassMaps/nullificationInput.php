<?php
namespace Transbank\Webpay\SoapClassMaps;

class nullificationInput {

    public $commerceId; //long
    public $buyOrder; //string
    public $authorizedAmount; //decimal
    public $authorizationCode; //string
    public $nullifyAmount; //decimal

}
