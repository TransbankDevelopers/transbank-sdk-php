<?php
namespace Transbank\Onepay;
/** 
 *
 *  @class TransactionCommitResponse
 *  Instances of this class represent the response from Transbank's servers
 *  to a TransactionCommitRequest
 *  @package Transbank;
 *  
*/

class TransactionCommitResponse extends BaseResponse implements \JsonSerializable {

    private $occ;
    private $authorizationCode;
    private $signature;
    private $transactionDesc;
    private $buyOrder;
    private $issuedAt;
    private $amount;
    private $installmentsAmount;
    private $installmentsNumber;

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

    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
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

    public function getTransactionDesc()
    {
        return $this->transactionDesc;
    }

    public function setTransactionDesc($transactionDesc)
    {
        $this->transactionDesc;
        return $this;
    }

    public function getBuyOrder()
    {
        return $this->buyOrder;
    }

    public function setBuyOrder($buyOrder)
    {
        $this->buyOrder = $buyOrder;
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

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getInstallmentsAmount()
    {
        return $this->installmentsAmount;
    }

    public function setInstallmentsAmount($installmentsAmount)
    {
        $this->installmentsAmount = $installmentsAmount;
        return $this;
    }

    public function getInstallmentsNumber()
    {
        return $this->installmentsNumber;
    }

    public function setInstallmentsNumber($installmentsNumber)
    {
        $this->installmentsNumber = $installmentsNumber;
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
        $responseResult = $json['result'];

        $this->setResponseCode($json['responseCode']);
        $this->setDescription($json['description']);
        $this->setOcc($responseResult['occ']);
        $this->setAuthorizationCode($responseResult['authorizationCode']);
        $this->setSignature($responseResult['signature']);
        $this->setTransactionDesc($responseResult['transactionDesc']);
        $this->setBuyOrder($responseResult['buyOrder']);
        $this->setIssuedAt($responseResult['issuedAt']);
        $this->setAmount($responseResult['amount']);
        $this->setInstallmentsAmount($responseResult['installmentsAmount']);
        $this->setInstallmentsNumber($responseResult['installmentsNumber']);
        return $this;
    }


}
