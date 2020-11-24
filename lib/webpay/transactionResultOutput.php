<?php
namespace Transbank\Webpay;

class transactionResultOutput
{
    public $accountingDate; //string
    public $buyOrder; //string
    public $cardDetail; //cardDetail
    public $detailOutput; //wsTransactionDetailOutput
    public $sessionId; //string
    public $transactionDate; //dateTime
    public $urlRedirection; //string
    public $VCI; //string
}
