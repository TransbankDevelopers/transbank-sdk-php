<?php

/**
 * Class InscriptionFinishResponse
 *
 * @category
 * @package Transbank\Patpass\PatpassComercio
 *
 */


namespace Transbank\Patpass\PatpassComercio;

class InscriptionFinishResponse
{
    public $status;
    public $code;

    public function __construct($httpCode)
    {
        $this->setCode($httpCode);

        if ($httpCode == 204) {
            $this->setStatus("OK");
            return;
        }
        if ($httpCode == 404) {
            $this->setStatus("Not Found");
            return;
        }
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}
