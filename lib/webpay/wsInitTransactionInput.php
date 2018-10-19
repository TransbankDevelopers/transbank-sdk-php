<?php
/**
 * Created by PhpStorm.
 * User: goncafa
 * Date: 10/18/18
 * Time: 12:41 PM
 */

namespace Transbank\Webpay;


class wsInitTransactionInput
{

    var $wSTransactionType; //wsTransactionType
    var $commerceId; //string
    var $buyOrder; //string
    var $sessionId; //string
    var $returnURL; //anyURI
    var $finalURL; //anyURI
    var $transactionDetails; //wsTransactionDetail
    var $wPMDetail; //wpmDetailInput

}
