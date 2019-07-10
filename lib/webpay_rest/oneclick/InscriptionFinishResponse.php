<?php


namespace Transbank\Webpay\Oneclick;

class InscriptionFinishResponse
{

    public $responseCode;
    public $tbkUser;
    public $authorizationCode;
    public $creditCardType;
    public $lastFourCardDigits;

    public function __construct($json)
    {
        $responseCode = isset($json["response_code"]) ? $json["response_code"] : null;
        $this->setResponseCode($responseCode);

        $tbkUser = isset($json["tbk_user"]) ? $json["tbk_user"] : null;
        $this->setTbkUser($tbkUser);

        $authorizationCode = isset($json["authorization_code"]) ? $json["authorization_code"] : null;
        $this->setAuthorizationCode($authorizationCode);

        $creditCardType = isset($json["credit_card_type"]) ? $json["credit_card_type"] : null;
        $this->setCreditCardType($creditCardType);

        $lastFourCardDigits = isset($json["last_four_card_digits"]) ? $json["last_four_card_digits"] : null;
        $this->setLastFourCardDigits($lastFourCardDigits);
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
     * @return InscriptionFinishResponse
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTbkUser()
    {
        return $this->tbkUser;
    }

    /**
     * @param mixed $tbkUser
     *
     * @return InscriptionFinishResponse
     */
    public function setTbkUser($tbkUser)
    {
        $this->tbkUser = $tbkUser;
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
     * @return InscriptionFinishResponse
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditCardType()
    {
        return $this->creditCardType;
    }

    /**
     * @param mixed $creditCardType
     *
     * @return InscriptionFinishResponse
     */
    public function setCreditCardType($creditCardType)
    {
        $this->creditCardType = $creditCardType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastFourCardDigits()
    {
        return $this->lastFourDigits;
    }

    /**
     * @param mixed $lastFourDigits
     *
     * @return InscriptionFinishResponse
     */
    public function setLastFourCardDigits($lastFourDigits)
    {
        $this->lastFourDigits = $lastFourDigits;
        return $this;
    }
}
