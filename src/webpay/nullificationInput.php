<?php
namespace Transbank\Webpay;

class nullificationInput
{
    public $commerceId; //long
    public $buyOrder; //string
    public $authorizedAmount; //decimal
    public $authorizationCode; //string
    public $nullifyAmount; //decimal
}
