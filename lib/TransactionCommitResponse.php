<?php
namespace Transbank;
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

    public function __construct(
                                $occ = null,
                                $authorizationCode = null,
                                $signature = null,
                                $transactionDesc = null,
                                $buyOrder = null,
                                $issuedAt = null,
                                $amount = null,
                                $installmentsAmount = null,
                                $installmentsNumber = null
                                )
    {
        $this->occ = $occ;
        $this->authorizationCode = $authorizationCode;
        $this->signature = $signature;
        $this->transactionDesc = $transactionDesc;
        $this->buyOrder = $buyOrder;
        $this->issuedAt = $issuedAt;
        $this->amount = $amount;
        $this->installmentAmount = $installmentsAmount;
        $this->installmentsNumber = $installmentsNumber;
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


}