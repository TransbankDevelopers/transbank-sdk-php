<?php
namespace Transbank;
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

    public function __construct($occ = null,
                                $ott = null,
                                $externalUniqueNumber = null,
                                $qrCodeAsBase64 = null)
    {

        $this->occ = $occ;
        $this->ott = $ott;
        $this->externalUniqueNumber = $externalUniqueNumber;
        $this->qrCodeAsBase64 = $qrCodeAsBase64;
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }

    public function getOcc() {
        return $this->occ;
    }

    public function setOcc($occ)
    {
        $this->occ = $occ;
    }

    public function getOtt()
    {
        return $this->ott;
    }

    public function setOtt($ott)
    {
        $this->ott = $ott;
    }

    public function getExternalUniqueNumber()
    {
        return $this->externalUniqueNumber;
    }

    public function setExternalUniqueNumber($externalUniqueNumber)
    {
        $this->externalUniqueNumber = $externalUniqueNumber;
    }

    public function getQrCodeAsBase64()
    {
        return $this->qrCodeAsBase64;
    }

    public function setQrCodeAsBase64($qrCodeAsBase64)
    {
        $this->qrCodeAsBase64 = $qrCodeAsBase64;
    }

}