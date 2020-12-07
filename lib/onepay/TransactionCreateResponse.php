<?php
namespace Transbank\Onepay;
/** 
 *
 *  @class TransactionCreateResponse
 *  Instances of this class represent the response from Transbank's servers
 *  to a TransactionCreateRequest
 *  @package Transbank;
 *  
*/

class TransactionCreateResponse extends BaseResponse implements \JsonSerializable {

    private $occ;
    private $ott;
    private $externalUniqueNumber;
    private $qrCodeAsBase64;
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
        $this->setOtt($json['result']['ott']);
        $this->setExternalUniqueNumber($json['result']['externalUniqueNumber']);
        $this->setQrCodeAsBase64($json['result']['qrCodeAsBase64']);
        $this->setIssuedAt($json['result']['issuedAt']);
        $this->setSignature($json['result']['signature']);

        return $this;
    }

    public function getOcc() {
        return $this->occ;
    }

    public function setOcc($occ)
    {
        $this->occ = $occ;
        return $this;
    }

    public function getOtt()
    {
        return $this->ott;
    }

    public function setOtt($ott)
    {
        $this->ott = $ott;
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

    public function getQrCodeAsBase64()
    {
        return $this->qrCodeAsBase64;
    }

    public function setQrCodeAsBase64($qrCodeAsBase64)
    {
        $this->qrCodeAsBase64 = $qrCodeAsBase64;
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
