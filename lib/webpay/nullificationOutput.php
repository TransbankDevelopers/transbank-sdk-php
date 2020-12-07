<?php
namespace Transbank\Webpay;

class nullificationOutput
{
    public $authorizationCode; //string
    public $authorizationDate; //dateTime
    public $balance; //decimal
    public $nullifiedAmount; //decimal
    public $token; //string
}
