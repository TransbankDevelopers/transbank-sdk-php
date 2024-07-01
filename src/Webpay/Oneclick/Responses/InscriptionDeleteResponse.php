<?php

namespace Transbank\Webpay\Oneclick\Responses;

class InscriptionDeleteResponse
{
    public bool $success = false;
    public ?int $code;

    public function __construct(bool $success, ?int $httpCode = null)
    {
        $this->success = $success;
        $this->code = $httpCode;
    }

    public function wasSuccessfull(): bool
    {
        return $this->success === true;
    }

    /**
     * @return ?int
     */
    public function getCode(): ?int
    {
        return $this->code;
    }
}
