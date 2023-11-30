<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;

class TransactionRefundResponse
{
    const TYPE_REVERSED = 'REVERSED';
    const TYPE_NULLIFY = 'NULLIFIED';
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
        $this->type = Utils::returnValueIfExists($json, 'type');
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->nullifiedAmount = Utils::returnValueIfExists($json, 'nullified_amount');
        $this->balance = Utils::returnValueIfExists($json, 'balance');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
    }

    public function success()
    {
        return $this->getType() === static::TYPE_REVERSED ||
            ($this->getType() === self::TYPE_NULLIFY && $this->getResponseCode() === 0);
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
