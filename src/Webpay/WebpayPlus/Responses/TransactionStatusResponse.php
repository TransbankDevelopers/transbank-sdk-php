<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\HasTransactionStatus;
use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\TransactionStatusEnum;

class TransactionStatusResponse
{
    use HasTransactionStatus;
    public $vci;

    public function __construct($json)
    {
        $this->vci = isset($json['vci']) ? $json['vci'] : null;
        $this->setTransactionStatusFields($json);
    }

    /**
     * Returns true if the transaction was approved.
     *
     * @return bool
     */
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

    /**
     * @return mixed
     */
    public function getVci()
    {
        return $this->vci;
    }

    /**
     * @param mixed $vci
     *
     * @return TransactionStatusResponse
     */
    public function setVci($vci)
    {
        $this->vci = $vci;

        return $this;
    }
}
