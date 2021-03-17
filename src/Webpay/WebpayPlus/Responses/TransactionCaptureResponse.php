<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\ResponseCodesEnum;

class TransactionCaptureResponse
{
    /**
     * TransactionCaptureResponse constructor.
     *
     * @param mixed $responseJson
     */
    public $token;
    public $authorizationCode;
    public $authorizationDate;
    public $capturedAmount;
    public $responseCode;

    public function __construct($json)
    {
        $this->token = isset($json['token']) ? $json['token'] : null;
        $this->authorizationCode = isset($json['authorization_code']) ? $json['authorization_code'] : null;
        $this->authorizationDate = isset($json['authorization_date']) ? $json['authorization_date'] : null;
        $this->capturedAmount = isset($json['captured_amount']) ? $json['captured_amount'] : null;
        $this->responseCode = isset($json['response_code']) ? $json['response_code'] : null;
    }

    public function isApproved()
    {
        return $this->responseCode === ResponseCodesEnum::RESPONSE_CODE_APPROVED;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     *
     * @return TransactionCaptureResponse
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param mixed $authorizationCode
     *
     * @return TransactionCaptureResponse
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationDate()
    {
        return $this->authorizationDate;
    }

    /**
     * @param mixed $authorizationDate
     *
     * @return TransactionCaptureResponse
     */
    public function setAuthorizationDate($authorizationDate)
    {
        $this->authorizationDate = $authorizationDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCapturedAmount()
    {
        return $this->capturedAmount;
    }

    /**
     * @param mixed $capturedAmount
     *
     * @return TransactionCaptureResponse
     */
    public function setCapturedAmount($capturedAmount)
    {
        $this->capturedAmount = $capturedAmount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $responseCode
     *
     * @return TransactionCaptureResponse
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }
}
