<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

class TransactionRefundResponse
{
    const TYPE_REVERSED = 'REVERSED';
    const TYPE_NULLIFY = 'NULLIFY';
    /**
     * @var mixed|null
     */
    public $type;
    /**
     * @var mixed|null
     */
    public $authorizationCode;
    /**
     * @var mixed|null
     */
    public $authorizationDate;
    /**
     * @var mixed|null
     */
    public $nullifiedAmount;
    /**
     * @var mixed|null
     */
    public $balance;
    /**
     * @var mixed|null
     */
    public $responseCode;

    /**
     * TransactionRefundResponse constructor.
     *
     * @param $json
     */
    public function __construct($json)
    {
        $this->type = isset($json['type']) ? $json['type'] : null;
        $this->authorizationCode = isset($json['authorization_code']) ? $json['authorization_code'] : null;
        $this->authorizationDate = isset($json['authorization_date']) ? $json['authorization_date'] : null;
        $this->nullifiedAmount = isset($json['nullified_amount']) ? $json['nullified_amount'] : null;
        $this->balance = isset($json['balance']) ? $json['balance'] : null;
        $this->responseCode = isset($json['response_code']) ? $json['response_code'] : null;
    }

    public function success()
    {
        if ($this->getType() === static::TYPE_REVERSED ||
            ($this->getType() === self::TYPE_NULLIFY && $this->getResponseCode() === 0));
    }

    /**
     * @return mixed|null
     */
    public function getNullifiedAmount()
    {
        return $this->nullifiedAmount;
    }

    /**
     * @param mixed|null $nullifiedAmount
     *
     * @return TransactionRefundResponse
     */
    public function setNullifiedAmount($nullifiedAmount)
    {
        $this->nullifiedAmount = $nullifiedAmount;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed|null $balance
     *
     * @return TransactionRefundResponse
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getResponseCode()
    {
        return (int) $this->responseCode;
    }

    /**
     * @param mixed|null $responseCode
     *
     * @return TransactionRefundResponse
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed|null $type
     *
     * @return TransactionRefundResponse
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param mixed|null $authorizationCode
     *
     * @return TransactionRefundResponse
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getAuthorizationDate()
    {
        return $this->authorizationDate;
    }

    /**
     * @param mixed|null $authorizationDate
     *
     * @return TransactionRefundResponse
     */
    public function setAuthorizationDate($authorizationDate)
    {
        $this->authorizationDate = $authorizationDate;

        return $this;
    }
}
