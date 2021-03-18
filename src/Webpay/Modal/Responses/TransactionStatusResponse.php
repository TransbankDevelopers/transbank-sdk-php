<?php

namespace Transbank\Webpay\Modal\Responses;

use Transbank\Utils\HasTransactionStatus;
use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\TransactionStatusEnum;

class TransactionStatusResponse
{
    use HasTransactionStatus;

    /**
     * TransactionCommitResponse constructor.
     *
     * @param mixed $json
     */
    public function __construct($json)
    {
        $this->setTransactionStatusFields($json);
    }

    public function isApproved()
    {
        return $this->getResponseCode() === ResponseCodesEnum::RESPONSE_CODE_APPROVED &&
            $this->getStatus() !== TransactionStatusEnum::STATUS_FAILED;
    }
}
