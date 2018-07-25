<?php
namespace Transbank;
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

        return $this->setResponseCode($json['responseCode'])
                    ->setDescription($json['description'])
                    ->setOcc($json['result']['occ'])
                    ->setExternalUniqueNumber($json['result']['externalUniqueNumber'])
                    ->setReverseCode($json['result']['reverseCode'])
                    ->setIssuedAt($json['result']['issuedAt'])
                    ->setSignature($json['result']['signature']);
    }



}
