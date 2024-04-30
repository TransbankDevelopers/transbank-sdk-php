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
     * @return mixed
     */
    public function getTbkUser()
    {
        return $this->tbkUser;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

}
