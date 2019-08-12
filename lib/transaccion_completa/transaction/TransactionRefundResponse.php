<?php

/**
 * Class TransactionRefundResponse
 *
 * @category
 * @package Transbank\TransaccionCompleta
 *
 */


namespace Transbank\TransaccionCompleta;


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
        $type = isset($json["type"]) ? $json["type"] : null;
        $this->setType($type);
        $authorizationCode = isset($json["authorization_code"]) ? $json["authorization_code"] : null;
        $this->setAuthorizationCode($authorizationCode);
        $authorizationDate = isset($json["authorization_date"]) ? $json["authorization_date"] : null;
        $this->setAuthorizationDate($authorizationDate);
        $nullifiedAmount = isset($json["nullified_amount"]) ? $json["nullified_amount"] : null;
        $this->setNullifiedAmount($nullifiedAmount);
        $balance = isset($json["balance"]) ? $json["balance"] : null;
        $this->setBalance($balance);
        $responseCode = isset($json["response_code"]) ? $json["response_code"] : null;
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
