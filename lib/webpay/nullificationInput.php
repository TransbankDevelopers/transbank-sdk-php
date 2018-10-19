<?php
/**
 * Created by PhpStorm.
 * User: goncafa
 * Date: 10/19/18
 * Time: 12:08 PM
 */

namespace Transbank\Webpay;


class nullificationInput {

    var $commerceId; //long
    var $buyOrder; //string
    var $authorizedAmount; //decimal
    var $authorizationCode; //string
    var $nullifyAmount; //decimal

}
