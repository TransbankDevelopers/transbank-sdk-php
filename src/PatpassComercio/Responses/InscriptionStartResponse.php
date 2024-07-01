<?php

/**
 * Class InscriptionStartResponse.
 *
 * @category
 */

namespace Transbank\PatpassComercio\Responses;

use Transbank\Utils\Utils;

class InscriptionStartResponse
{
    public ?string $token;
    public ?string $urlWebpay;

    /**
     * InscriptionStartResponse constructor.
     *
     * @param array $json
     */
    public function __construct(array $json)
    {
        $this->token = Utils::returnValueIfExists($json, 'token');
        $this->urlWebpay = Utils::returnValueIfExists($json, 'url');
    }

    /**
     * @return ?string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return ?string
     */
    public function getUrlWebpay(): ?string
    {
        return $this->urlWebpay;
    }

}
