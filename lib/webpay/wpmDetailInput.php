<?php
/**
 * Created by PhpStorm.
 * User: goncafa
 * Date: 10/18/18
 * Time: 12:36 PM
 */

namespace Transbank\Webpay;


class wpmDetailInput
{

    var $serviceId; //string
    var $cardHolderId; //string
    var $cardHolderName; //string
    var $cardHolderLastName1; //string
    var $cardHolderLastName2; //string
    var $cardHolderMail; //string
    var $cellPhoneNumber; //string
    var $expirationDate; //dateTime
    var $commerceMail; //string
    var $ufFlag; //boolean

}
