<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\Utils;

class TransactionCaptureResponse
{
    /**
     * TransactionCaptureResponse constructor.
     *
     * @param array $json
     */
    public string|null $authorizationCode;
    public string|null $authorizationDate;
    public ?float $capturedAmount;
    public int|null $responseCode;

    public function __construct(array $json)
    {
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->capturedAmount = Utils::returnValueIfExists($json, 'captured_amount');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->responseCode === ResponseCodesEnum::RESPONSE_CODE_APPROVED;
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
    public function getAuthorizationDate(): string|null
    {
        return $this->authorizationDate;
    }

    /**
     * @return ?float
     */
    public function getCapturedAmount(): ?float
    {
        return $this->capturedAmount;
    }

    /**
     * @return int|null
     */
    public function getResponseCode(): int|null
    {
        return $this->responseCode;
    }
}
