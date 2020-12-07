<?php

/**
 * Class TransactionRefundResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


use Transbank\Utils\Utils;

class TransactionRefundResponse
{
    public $type;
    public $authorizationCode;
    public $authorizationDate;
    public $nullifiedAmount;
    public $balance;
    public $responseCode;

    public function __construct($json)
    {
        $type =  Utils::returnValueIfExists($json, "type");
        $this->setType($type);
        $authorizationCode =  Utils::returnValueIfExists($json, "authorization_code");
        $this->setAuthorizationCode($authorizationCode);
        $authorizationDate =  Utils::returnValueIfExists($json, "authorization_date");
        $this->setAuthorizationDate($authorizationDate);
        $nullifiedAmount =  Utils::returnValueIfExists($json, "nullified_amount");
        $this->setNullifiedAmount($nullifiedAmount);
        $balance =  Utils::returnValueIfExists($json, "balance");
        $this->setBalance($balance);
        $responseCode =  Utils::returnValueIfExists($json, "response_code");
        $this->setResponseCode($responseCode);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return TransactionRefundResponse
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param mixed $authorizationCode
     * @return TransactionRefundResponse
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationDate()
    {
        return $this->authorizationDate;
    }

    /**
     * @param mixed $authorizationDate
     * @return TransactionRefundResponse
     */
    public function setAuthorizationDate($authorizationDate)
    {
        $this->authorizationDate = $authorizationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNullifiedAmount()
    {
        return $this->nullifiedAmount;
    }

    /**
     * @param mixed $nullifiedAmount
     * @return TransactionRefundResponse
     */
    public function setNullifiedAmount($nullifiedAmount)
    {
        $this->nullifiedAmount = $nullifiedAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     * @return TransactionRefundResponse
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $responseCode
     *
     *
     * @return TransactionRefundResponse
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
        return $this;
    }


}
