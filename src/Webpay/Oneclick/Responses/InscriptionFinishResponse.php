<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\Utils;

class InscriptionFinishResponse
{
    public $responseCode;
    public $tbkUser;
    public $authorizationCode;
    public $cardType;
    public $cardNumber;

    public function __construct($json)
    {
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
        $this->tbkUser = Utils::returnValueIfExists($json, 'tbk_user');
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->cardType = Utils::returnValueIfExists($json, 'card_type');
        $this->cardNumber = Utils::returnValueIfExists($json, 'card_number');
    }

    public function isApproved()
    {
        return $this->getResponseCode() === ResponseCodesEnum::RESPONSE_CODE_APPROVED;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return (int) $this->responseCode;
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
