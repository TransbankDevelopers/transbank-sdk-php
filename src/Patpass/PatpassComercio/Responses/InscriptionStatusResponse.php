<?php

/**
 * Class InscriptionStatusResponse.
 *
 * @category
 */

namespace Transbank\Patpass\PatpassComercio\Responses;

use Transbank\Utils\Utils;

class InscriptionStatusResponse
{
    public $status;
    public $urlVoucher;

    public function __construct($json)
    {
        $this->status = Utils::returnValueIfExists($json, 'authorized');
        $this->urlVoucher = Utils::returnValueIfExists($json, 'voucherUrl');
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
     *
     * @return InscriptionStatusResponse
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
     *
     * @return InscriptionStatusResponse
     */
    public function setUrlVoucher($urlVoucher)
    {
        $this->urlVoucher = $urlVoucher;

        return $this;
    }
}
