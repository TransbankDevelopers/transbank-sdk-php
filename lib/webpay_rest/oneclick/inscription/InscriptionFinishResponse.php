<?php


namespace Transbank\Webpay\Oneclick;

class InscriptionFinishResponse
{

    public $responseCode;
    public $tbkUser;
    public $authorizationCode;
    public $cardType;
    public $cardNumber;

    public function __construct($json)
    {
        $responseCode = isset($json["response_code"]) ? $json["response_code"] : null;
        $this->setResponseCode($responseCode);

        $tbkUser = isset($json["tbk_user"]) ? $json["tbk_user"] : null;
        $this->setTbkUser($tbkUser);

        $authorizationCode = isset($json["authorization_code"]) ? $json["authorization_code"] : null;
        $this->setAuthorizationCode($authorizationCode);

        $cardType = isset($json["card_type"]) ? $json["card_type"] : null;
        $this->setCardType($cardType);

        $cardNumber = isset($json["card_number"]) ? $json["card_number"] : null;
        $this->setCardNumber($cardNumber);
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
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param mixed $cardType
     *
     * @return InscriptionFinishResponse
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param mixed $cardNumber
     *
     * @return InscriptionFinishResponse
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }
}
