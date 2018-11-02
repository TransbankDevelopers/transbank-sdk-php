<?php


namespace Transbank\Webpay\SoapClassMaps;


class WsInitTransactionMallInput
{
    public $wSTransactionType;//wsTransactionType
    public $commerceId;//string
    public $buyOrder;//string
    public $sessionId;//string
    public $returnURL;//anyURI
    public $finalURL;//anyURI
    public $transactionDetails;//wsTransactionMallDetail
    public $wPMDetail;//wpmDetailMallInput

}
