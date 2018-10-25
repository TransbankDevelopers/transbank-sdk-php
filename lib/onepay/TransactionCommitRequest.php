<?php
namespace Transbank\Onepay;
/**
 * 
 * class TransactionCommitRequest
 * Commits a request to actually execute.
 * If not done transaction will reverse itself after some time.
 * 
 */

 class TransactionCommitRequest extends BaseRequest implements \JsonSerializable 
 {
    private $occ;
    private $externalUniqueNumber;
    private $issuedAt;
    private $signature;

    public function __construct($occ = null, $externalUniqueNumber = null, $issuedAt = null)
    {
        // Can be initialized as object to be filled with params later,
        // but cannot "unset" a property to null.
        $this->occ = $occ;
        $this->externalUniqueNumber = $externalUniqueNumber;
        $this->issuedAt = $issuedAt;
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }
    
    public function getOcc()
    {
        return $this->occ;
    }

    public function setOcc($occ)
    {
        if (!$occ) {
            throw new \Exception('Occ cannot be set to null.');
        }
        
        $this->occ = $occ;
        return $this;
    }

    public function getExternalUniqueNumber()
    {
        return $this->externalUniqueNumber;
    }

    public function setExternalUniqueNumber($externalUniqueNumber)
    {
        if(!$externalUniqueNumber) {
            throw new \Exception('externalUniqueNumber cannot be null.');
        }
        $this->externalUniqueNumber = $externalUniqueNumber;
        return $this;
    }

    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    public function setIssuedAt($issuedAt)
    {
        if(!$issuedAt) {
            throw new \Exception('issuedAt cannot be null.');
        }
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
