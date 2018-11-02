<?php


namespace Transbank\Webpay\SoapClassMaps;


class WsCompleteInitTransactionInput
{
    public $transactionType; //wsCompleteTransactionType
    public $commerceId; //string
    public $buyOrder; //string
    public $sessionId; //string
    public $cardDetail; //completeCardDetail
    public $transactionDetails; //wsCompleteTransactionDetail

}
