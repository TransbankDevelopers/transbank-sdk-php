<?php

namespace Transbank\Common\Responses;

use Transbank\Utils\Utils;

class DeferredCaptureHistoryResponse
{
    public $type;
    public $amount;
    public $authorizationCode;
    public $authorizationDate;
    public $totalAmount;
    public $expirationDate;
    public $responseCode;

    public function __construct($json)
    {
        $type = Utils::returnValueIfExists($json, 'type');
        $this->setType($type);
        $amount = Utils::returnValueIfExists($json, 'amount');
        $this->setAmount($amount);
        $authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->setAuthorizationCode($authorizationCode);
        $authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->setAuthorizationDate($authorizationDate);
        $totalAmount = Utils::returnValueIfExists($json, 'total_amount');
        $this->setTotalAmount($totalAmount);
        $expirationDate = Utils::returnValueIfExists($json, 'expiration_date');
        $this->setExpirationDate($expirationDate);
        $responseCode = Utils::returnValueIfExists($json, 'response_code');
        $this->setResponseCode($responseCode);
    }
}
