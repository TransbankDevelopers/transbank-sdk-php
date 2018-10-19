<?php
/**
 * Created by PhpStorm.
 * User: goncafa
 * Date: 10/18/18
 * Time: 12:38 PM
 */

namespace Transbank\Webpay;


class transactionResultOutput
{

    var $accountingDate; //string
    var $buyOrder; //string
    var $cardDetail; //cardDetail
    var $detailOutput; //wsTransactionDetailOutput
    var $sessionId; //string
    var $transactionDate; //dateTime
    var $urlRedirection; //string
    var $VCI; //string

}
