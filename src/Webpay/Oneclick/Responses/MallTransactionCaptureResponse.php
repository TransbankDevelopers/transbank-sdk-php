<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class MallTransactionCaptureResponse
{
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
