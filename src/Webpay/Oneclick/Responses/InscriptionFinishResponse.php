<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\Utils;

class InscriptionFinishResponse
{
    public int $responseCode;
    public string $tbkUser;
    public string $authorizationCode;
    public string $cardType;
    public string $cardNumber;

    public function __construct(array $json)
    {
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
        $this->tbkUser = Utils::returnValueIfExists($json, 'tbk_user');
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->cardType = Utils::returnValueIfExists($json, 'card_type');
        $this->cardNumber = Utils::returnValueIfExists($json, 'card_number');
    }

    public function isApproved(): bool
    {
        return $this->getResponseCode() === ResponseCodesEnum::RESPONSE_CODE_APPROVED;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @return string
     */
    public function getTbkUser(): string
    {
        return $this->tbkUser;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode(): string
    {
        return $this->authorizationCode;
    }

    /**
     * @return string
     */
    public function getCardType(): string
    {
        return $this->cardType;
    }

    /**
     * @return string
     */
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }
}
