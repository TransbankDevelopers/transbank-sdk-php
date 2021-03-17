<?php

namespace Transbank\Webpay\Oneclick\Responses;

class InscriptionDeleteResponse
{
    public $success = false;
    public $code;

    public function __construct($success, $httpCode = null)
    {
        $this->success = $success;
        $this->code = $httpCode;
    }

    public function wasSuccessfull()
    {
        return $this->success === true;
    }

    /**
     * @return mixed|null
     */
    public function getCode()
    {
        return $this->code;
    }
}
