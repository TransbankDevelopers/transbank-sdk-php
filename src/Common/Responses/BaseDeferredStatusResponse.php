<?php

namespace Transbank\Common\Responses;

use Transbank\Utils\Utils;

class BaseDeferredStatusResponse
{
    public $authorizationCode;
    public $authorizationDate;
    public $totalAmount;
    public $expirationDate;
    public $responseCode;

    public function __construct($json)
    {
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->totalAmount = Utils::returnValueIfExists($json, 'total_amount');
        $this->expirationDate = Utils::returnValueIfExists($json, 'expiration_date');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
    }

    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    public function setAuthorizationDate($authorizationDate)
    {
        $this->authorizationDate = $authorizationDate;

        return $this;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }
}
