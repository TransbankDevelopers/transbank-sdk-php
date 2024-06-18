<?php

/**
 * Class InscriptionFinishResponse.
 *
 * @category
 */

namespace Transbank\PatpassComercio\Responses;

class InscriptionFinishResponse
{
    public $status;
    public $code;

    public function __construct($httpCode)
    {
        $this->code = $httpCode;

        if ($httpCode == 204) {
            $this->status = 'OK';
        }
        if ($httpCode == 404) {
            $this->status = 'Not Found';
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
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

}
