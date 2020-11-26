<?php
namespace Transbank\Webpay;

class wpmDetailInput
{
    public $serviceId; //string
    public $cardHolderId; //string
    public $cardHolderName; //string
    public $cardHolderLastName1; //string
    public $cardHolderLastName2; //string
    public $cardHolderMail; //string
    public $cellPhoneNumber; //string
    public $expirationDate; //dateTime
    public $commerceMail; //string
    public $ufFlag; //boolean
}
