<?php
namespace Transbank\Onepay;
/**
 * class RefundCreateResponse
 * Model object for the response to a Refund creation attempt.
 * 
 * @package Transbank;
 */
class RefundCreateResponse extends BaseResponse implements \JsonSerializable {
    private $occ;
    private $externalUniqueNumber;
    private $reverseCode;
    private $issuedAt;
    private $signature;

    public function __construct($json)
    {
        $this->fromJSON($json);
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

    public function getReverseCode()
    {
        return $this->reverseCode;
    }

    public function setReverseCode($reverseCode) {
        $this->reverseCode = $reverseCode;
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

    public function fromJSON($json)
    {
        if(is_string($json)) {
            $json = json_decode($json, true);
        }
        if (!is_array($json)) {
            throw new \Exception('Given value must be an associative array or a string that can be converted to an associative array with json_decode()');
        }

        $this->setResponseCode($json['responseCode']);
        $this->setDescription($json['description']);
        $this->setOcc($json['result']['occ']);
        $this->setExternalUniqueNumber($json['result']['externalUniqueNumber']);
        $this->setReverseCode($json['result']['reverseCode']);
        $this->setIssuedAt($json['result']['issuedAt']);
        $this->setSignature($json['result']['signature']);
        return $this;
    }



}
