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
        return $this->getResponseCode() === ResponseCodesEnum::RESPONSE_CODE_APPROVED &&
            $this->getStatus() !== TransactionStatusEnum::STATUS_FAILED;
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
