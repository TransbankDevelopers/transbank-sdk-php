<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\Utils;

class InscriptionFinishResponse
{
    public int|null $responseCode;
    public string|null $tbkUser;
    public string|null $authorizationCode;
    public string|null $cardType;
    public string|null $cardNumber;

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
     * @return int|null
     */
    public function getResponseCode(): int|null
    {
        return $this->responseCode;
    }

    /**
     * @return string|null
     */
    public function getTbkUser(): string|null
    {
        return $this->tbkUser;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationCode(): string|null
    {
        return $this->authorizationCode;
    }

    /**
     * @return string|null
     */
    public function getCardType(): string|null
    {
        return $this->cardType;
    }

    /**
     * @return string|null
     */
    public function getCardNumber(): string|null
    {
        return $this->cardNumber;
    }
}
