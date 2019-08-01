<?php

/**
 * Class InscriptionFinishResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


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
        $tbkUser = isset($json["tbk_user"]) ? $json["tbk_user"] : null;
        $authorizationCode = isset($json["authorization_code"]) ? $json["authorization_code"] : null;
        $creditCardType = isset($json["credit_card_type"]) ? $json["credit_card_type"] : null;
        $lastFourCardDigits = isset($json["last_four_card_digits"]) ? $json["last_four_card_digits"] : null;
        $this->setResponseCode($responseCode);
        $this->setTbkUser($tbkUser);
        $this->setAuthorizationCode($authorizationCode);
        $this->setCreditCardType($creditCardType);
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
        return $this->lastFourCardDigits;
    }

    /**
     * @param mixed $lastFourCardDigits
     */
    public function setLastFourCardDigits($lastFourCardDigits)
    {
        $this->lastFourCardDigits = $lastFourCardDigits;
        return $this;
    }


}
