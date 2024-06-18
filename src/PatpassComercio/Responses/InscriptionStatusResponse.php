<?php

/**
 * Class InscriptionStatusResponse.
 *
 * @category
 */

namespace Transbank\PatpassComercio\Responses;

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
     * @return mixed
     */
    public function getUrlVoucher()
    {
        return $this->urlVoucher;
    }

}
