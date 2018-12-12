<?php
namespace Transbank\Webpay;

class nullificationInput {
    var $commerceId; //long
    var $buyOrder; //string
    var $authorizedAmount; //decimal
    var $authorizationCode; //string
    var $nullifyAmount; //decimal
}
