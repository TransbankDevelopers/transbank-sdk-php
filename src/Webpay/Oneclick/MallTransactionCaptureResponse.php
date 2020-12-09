<?php


namespace Transbank\Webpay\Oneclick;

class MallTransactionCaptureResponse
{
    public $authorizationCode;
    public $authorizationDate;
    public $capturedAmount;
    public $responseCode;

    public function __construct($json)
    {
        $this->setAuthorizationCode($json["authorization_code"] ?? null);
        $this->setAuthorizationDate($json["authorization_date"] ?? null);
        $this->setCapturedAmount($json["captured_amount"] ?? null);
        $this->setResponseCode($json["captured_amount"] ?? null);
    }

    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
        return $this;
    }

    public function getAuthorizationDate()
    {
        return $this->authorizationDate;
    }

    public function setAuthorizationDate($authorizationDate)
    {
        $this->authorizationDate = $authorizationDate;
        return $this;
    }

    public function getCapturedAmount()
    {
        return $this->capturedAmount;
    }

    public function setCapturedAmount($capturedAmount)
    {
        $this->capturedAmount = $capturedAmount;
        return $this;
    }

    public function getResponseCode()
    {
        return $this->reponseCode;
    }

    public function setResponseCode($reponseCode)
    {
        $this->reponseCode = $reponseCode;
        return $this;
    }
}
