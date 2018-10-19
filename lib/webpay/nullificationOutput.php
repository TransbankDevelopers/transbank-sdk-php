<?php
/**
 * Created by PhpStorm.
 * User: goncafa
 * Date: 10/19/18
 * Time: 12:09 PM
 */

namespace Transbank\Webpay;


class nullificationOutput {

    var $authorizationCode; //string
    var $authorizationDate; //dateTime
    var $balance; //decimal
    var $nullifiedAmount; //decimal
    var $token; //string

}
