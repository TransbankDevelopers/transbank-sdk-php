<?php


namespace Transbank\Webpay\SoapClassMaps;


class WsCompleteAuthorizeOutput
{
    public $accountingDate; //string
    public $buyOrder; //string
    public $detailsOutput; //wsTransactionCompleteDetailOutput
    public $sessionId; //string
    public $transactionDate; //dateTime
}
