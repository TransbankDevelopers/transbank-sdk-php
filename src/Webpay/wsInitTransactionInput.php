<?php
namespace Transbank\Webpay;

class wsInitTransactionInput
{
    public $wSTransactionType; //wsTransactionType
    public $commerceId; //string
    public $buyOrder; //string
    public $sessionId; //string
    public $returnURL; //anyURI
    public $finalURL; //anyURI
    public $transactionDetails; //wsTransactionDetail
    public $wPMDetail; //wpmDetailInput
}
