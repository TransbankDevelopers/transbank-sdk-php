<?php

/**
 * Class InscriptionStatusResponse
 *
 * @category
 * @package Transbank\Patpass\PatpassComercio\inscription
 *
 */


namespace Transbank\Patpass\PatpassComercio;


class InscriptionStatusResponse
{
    public $status;
    public $urlVoucher;

    public function __construct($json)
    {
        $status = isset($json["status"]) ? $json["status"] : null;
        $this->setStatus($status);
        $urlVoucher = isset($json["urlVoucher"]) ? $json["urlVoucher"] : null;
        $this->setUrlVoucher($urlVoucher);
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
    public function getUrlVoucher()
    {
        return $this->urlVoucher;
    }

    /**
     * @param mixed $urlVoucher
     */
    public function setUrlVoucher($urlVoucher)
    {
        $this->urlVoucher = $urlVoucher;
        return $this;
    }


}
