<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class MallTransactionCaptureResponse
{
    public $authorizationCode;
    public $authorizationDate;
    public $capturedAmount;
    public $responseCode;

    public function __construct($json)
    {
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->capturedAmount = Utils::returnValueIfExists($json, 'captured_amount');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
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
