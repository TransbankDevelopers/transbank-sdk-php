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
    public ?string $status;
    public ?string $urlVoucher;

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
     * @return ?string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return ?string
     */
    public function getUrlVoucher(): ?string
    {
        return $this->urlVoucher;
    }

}
