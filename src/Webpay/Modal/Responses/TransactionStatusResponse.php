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
        if($this->getResponseCode() !== ResponseCodesEnum::RESPONSE_CODE_APPROVED) {
            return false;
        }

        switch($this->getStatus()) {
            case TransactionStatusEnum::STATUS_CAPTURED:
            case TransactionStatusEnum::STATUS_REVERSED:
            case TransactionStatusEnum::STATUS_NULLIFIED:
            case TransactionStatusEnum::STATUS_AUTHORIZED:
            case TransactionStatusEnum::STATUS_PARTIALLY_NULLIFIED:
                return true;
            default :
                return false;
        }
    }
}
