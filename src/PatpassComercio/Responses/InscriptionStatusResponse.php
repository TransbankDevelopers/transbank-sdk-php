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
    public string|null $status;
    public string|null $urlVoucher;

    /**
     * InscriptionStatusResponse constructor.
     *
     * @param array $json
     */

    public function __construct(array $json)
    {
        $this->status = Utils::returnValueIfExists($json, 'authorized');
        $this->urlVoucher = Utils::returnValueIfExists($json, 'voucherUrl');
    }

    /**
     * @return string|null
     */
    public function getStatus(): string|null
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getUrlVoucher(): string|null
    {
        return $this->urlVoucher;
    }
}
