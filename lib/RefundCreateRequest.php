<?php
namespace Transbank;
/**
 * 
 * class RefundCreateRequest
 * Creates a request object to be sent to Transbank to attempt a refund
 * 
 * @package Transbank;
 * 
 * 
 */
class RefundCreateRequest extends BaseRequest implements \JsonSerializable {

    private $refundAmount;
    private $occ;
    private $externalUniqueNumber;
    private $authorizationCode;
    private $issuedAt;
    private $signature;

    public function __construct($refundAmount = null, $occ = null,
                                $externalUniqueNumber = null,
                                $authorizationCode = null,
                                $issuedAt = null, $signature = null)
    {
        $this->refundAmount = $refundAmount;
        $this->occ = $occ;
        $this->externalUniqueNumber = $externalUniqueNumber;
        $this->authorizationCode = $authorizationCode;
        $this->issuedAt = $issuedAt;
        $this->signature = $signature;
    }

    public function getRefundAmount()
    {
        return $this->refundAmount;
    }
    public function setRefundAmount($refundAmount)
    {
        $this->refundAmount = $refundAmount;
        return $this;
    }
    public function getOcc()
    {
        return $this->occ;
    }

    public function setOcc($occ)
    {
        $this->occ = $occ;
        return $this;
    }

    public function getExternalUniqueNumber()
    {
        return $this->externalUniqueNumber;
    }

    public function setExternalUniqueNumber($externalUniqueNumber)
    {
        $this->externalUniqueNumber = $externalUniqueNumber;
        return $this;
    }

    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;
        return $this;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function setSignature($signature)
    {
        $this->signature = $signature;
        return $this;
    }
}
