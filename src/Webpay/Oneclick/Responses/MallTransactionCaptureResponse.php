<?php

namespace Transbank\Webpay\Oneclick\Responses;

class MallTransactionCaptureResponse
{
    public $authorizationCode;
    public $authorizationDate;
    public $capturedAmount;
    public $responseCode;

    public function __construct($json)
    {
        $this->setAuthorizationCode(isset($json['authorization_code']) ? $json['authorization_code'] : null);
        $this->setAuthorizationDate(isset($json['authorization_date']) ? $json['authorization_date'] : null);
        $this->setCapturedAmount(isset($json['captured_amount']) ? $json['captured_amount'] : null);
        $this->setResponseCode(isset($json['response_code']) ? $json['response_code'] : null);
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
        return $this->responseCode;
    }

    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }
}
