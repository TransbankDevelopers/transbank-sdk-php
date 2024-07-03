<?php

namespace Transbank\Webpay\Oneclick\Responses;

class InscriptionDeleteResponse
{
    public bool $success = false;
    public int|null $code;

    public function __construct(bool $success, int|null $httpCode = null)
    {
        $this->success = $success;
        $this->code = $httpCode;
    }

    public function wasSuccessfull(): bool
    {
        return $this->success === true;
    }

    /**
     * @return int|null
     */
    public function getCode(): int|null
    {
        return $this->code;
    }
}
