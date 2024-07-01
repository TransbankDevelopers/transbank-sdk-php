<?php

/**
 * Class InscriptionFinishResponse.
 *
 * @category
 */

namespace Transbank\PatpassComercio\Responses;

class InscriptionFinishResponse
{
    public ?string $status;
    public int $code;

    public function __construct(int $httpCode)
    {
        $this->code = $httpCode;
        $this->status = null;

        if ($httpCode == 204) {
            $this->status = 'OK';
        }
        if ($httpCode == 404) {
            $this->status = 'Not Found';
        }
    }

    /**
     * @return ?string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }
}
