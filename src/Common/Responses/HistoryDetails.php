<?php

namespace Transbank\Common\Responses;

use Transbank\Utils\Utils;

class HistoryDetails
{

    public $type;
    public $amount;
    public $authorizationCode;
    public $authorizationDate;
    public $totalAmount;
    public $expirationDate;
    public $responseCode;

    public function __construct(
        $type,
        $amount,
        $authorizationCode,
        $authorizationDate,
        $totalAmount,
        $expirationDate,
        $responseCode
    ) {
        $this->type = $type;
        $this->amount = $amount;
        $this->authorizationCode = $authorizationCode;
        $this->authorizationDate = $authorizationDate;
        $this->totalAmount = $totalAmount;
        $this->expirationDate = $expirationDate;
        $this->responseCode = $responseCode;
    }

    public static function createFromArray(array $array)
    {
        $type = isset($array['type']) ? $array['type'] : null;
        $amount = isset($array['amount']) ? $array['amount'] : null;
        $authorizationCode = isset($array['authorization_code']) ? $array['authorization_code'] : null;
        $authorizationDate = isset($array['authorization_date']) ? $array['authorization_date'] : null;
        $totalAmount = isset($array['total_amount']) ? $array['total_amount'] : null;
        $expirationDate = isset($array['expiration_date']) ? $array['expiration_date'] : null;
        $responseCode = isset($array['response_code']) ? $array['response_code'] : null;

        return new static($type, $amount, $authorizationCode, $authorizationDate, $totalAmount, $expirationDate,
            $responseCode);
    }

}
