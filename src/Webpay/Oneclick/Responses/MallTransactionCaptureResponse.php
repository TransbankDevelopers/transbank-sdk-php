<?php

namespace Transbank\Webpay\Oneclick\Responses;

use Transbank\Utils\Utils;

class MallTransactionCaptureResponse
{
    public ?string $authorizationCode;
    public ?string $authorizationDate;
    public ?float $capturedAmount;
    public ?int $responseCode;

    public function __construct(array $json)
    {
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->capturedAmount = Utils::returnValueIfExists($json, 'captured_amount');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    public function getAuthorizationDate(): ?string
    {
        return $this->authorizationDate;
    }

    public function getCapturedAmount(): ?float
    {
        return $this->capturedAmount;
    }

    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }
}
