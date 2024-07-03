<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\HasTransactionStatus;
use Transbank\Utils\ResponseCodesEnum;
use Transbank\Utils\TransactionStatusEnum;
use Transbank\Utils\Utils;

class TransactionStatusResponse
{
    use HasTransactionStatus;
    public string|null $vci;

    public function __construct(array $json)
    {
        $this->vci = Utils::returnValueIfExists($json, 'vci');
        $this->setTransactionStatusFields($json);
    }

    /**
     * Returns true if the transaction was approved.
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        if ($this->getResponseCode() !== ResponseCodesEnum::RESPONSE_CODE_APPROVED) {
            return false;
        }

        switch ($this->getStatus()) {
            case TransactionStatusEnum::STATUS_CAPTURED:
            case TransactionStatusEnum::STATUS_REVERSED:
            case TransactionStatusEnum::STATUS_NULLIFIED:
            case TransactionStatusEnum::STATUS_AUTHORIZED:
            case TransactionStatusEnum::STATUS_PARTIALLY_NULLIFIED:
                return true;
            default:
                return false;
        }
    }

    /**
     * @return string|null
     */
    public function getVci(): string|null
    {
        return $this->vci;
    }

    /**
     * @param string $vci
     *
     * @return TransactionStatusResponse
     */
    public function setVci(string $vci): TransactionStatusResponse
    {
        $this->vci = $vci;

        return $this;
    }
}
