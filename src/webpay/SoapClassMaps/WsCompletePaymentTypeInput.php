<?php


namespace Transbank\Webpay\SoapClassMaps;


class WsCompletePaymentTypeInput
{
    public $commerceCode; //string
    public $buyOrder; //string
    public $queryShareInput; //wsCompleteQueryShareInput
    public $gracePeriod; //boolean


}
